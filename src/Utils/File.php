<?php 

namespace App\Utils;

// Utilities for dealing with files
class File
{

    /**
     * Moves a file from it's currently location to a new destination, being
     * careful to not overwrite anything there.
     *
     * @param  string $src Path to the current file
     * @param  string $dst Path to where you want it go
     * @return mixed New path or false on error
     */
    static public function moveFileUniquely($src, $dst)
    {

        // The file doesn't exist, so straight move it
        if (!file_exists($dst)) {
            rename($src, $dst);
            return $dst;
        }

        // Try different suffixes on the file until a match doesn't exist
        $dir = dirname($dst);
        $file = pathinfo($dst, PATHINFO_FILENAME);
        $i = 1;
        $ext = pathinfo($dst, PATHINFO_EXTENSION);
        while (file_exists($dst = $dir.'/'.$file.$i.'.'.$ext)) {
            $i++;
        }

        // Move the file and return the new path
        rename($src, $dst);
        return $dst;

    }

    /**
     * Create a number of subdirectories witihin the provided folder. This
     * is done to get around filesystem limitations when you create too many
     * files in a given directory.  Also makes FTP and SSH listings faster.
     *
     * @param  string $dir    The directory to create sub directories in
     * @param  number $depth  How deep to make them
     * @param  number $length How many to make per depth
     * @return mixed New path (with trailing slash) or false on error
     */
    static public function makeSubDirs($dir, $depth = 2, $length = 16)
    {

        // Make sure the destination is writeable
        if (!is_dir($dir) || !is_writable($dir)) { return false;
        }

        // Make sure the destination ends in a slash
        $dir = self::addTrailingSlash($dir);

        // Loop through the depth, making directories
        for ($i=0; $i<$depth; $i++) {
            $new_dir = str_pad(mt_rand(0, $length - 1), strlen($length), '0', STR_PAD_LEFT);
            $dir .= $new_dir.'/'; // Update our directory path
            if (is_dir($dir)) { continue; // This directory already exists, go to next depth
            }
            if (!mkdir($dir, 0775)) { return false; // Make the dir or return false if error
            }
            chmod($dir, 0775); // The mkdir permissions weren't taking
        }

        // Return new path
        return $dir;
    }

    /**
     * Combine a bunch of comon operations on files into a single
     * command: Simplify the filename, make it unique, and store it in a nested
     * directory
     *
     * @param  mixed  $src Path to the uploaded file or FILES array or like Laravel's `Input::file('image')`
     * @param  string $dst Directory of where to save the final file
     * @return mixed New path or false on error
     */
    static public function organizeFile($src, $dst_dir)
    {

        // Make sure the destination ends in a slash
        $dst_dir = self::addTrailingSlash($dst_dir);

        // If $src is a FILES array, get the tmp and real filenames out
        if (is_array($src)) {
            $filename = $src['name'];
            $src = $src['tmp_name'];

            // If $src is an instance of Symfony's UploadedFile class
        } else if (is_a($src, 'Symfony\Component\HttpFoundation\File\UploadedFile')) {
            $filename = $src->getClientOriginalName();
            $src = $src->getRealPath();

            // Otherwise, use the filename of $src for the destination path
        } else {
            $filename = basename($src);
        }

        // Make nested sub directories
        if (!($dst_dir = self::makeSubDirs($dst_dir))) { return false;
        }

        // Make the file a safe filename
        $filename = preg_replace('/[^a-z0-9-_.]/', '', strtolower($filename));

        // Move the file out of it's current directory, into the target destination
        if (!($path = self::moveFileUniquely($src, $dst_dir.$filename))) { return false;
        }

        // Make the file group writeable
        chmod($path, 0664);

        // Return the final path
        return $path;
    }

    /**
     * Add the trailing slash onto a directory name
     *
     * @param  string $dir The path to a directory
     * @return string The slash added to the name
     */
    static public function addTrailingSlash($dir)
    {
        if (substr($dir, -1, 1) != '/') { $dir .= '/';
        }
        return $dir;
    }

    /**
     * Get the document root.  No trailing slash.
     *
     * @return string
     */
    public static function documentRoot()
    {
        if (function_exists('public_path')) { return public_path();
        } else if (!empty($_SERVER['DOCUMENT_ROOT'])) { return $_SERVER['DOCUMENT_ROOT'];
        } else { throw new \Exception('DOCUMENT_ROOT not defined');
        }
    }

}