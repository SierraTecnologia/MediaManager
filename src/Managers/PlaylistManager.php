<?php

namespace MediaManager\Managers;

use MediaManager\Entities\PlaylistEntity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface PlaylistManager.
 *
 * @package Core\Contracts
 */
interface PlaylistManager
{
    /**
     * Create a post.
     *
     * @param  array $attributes
     * @return PlaylistEntity
     */
    public function create(array $attributes): PlaylistEntity;

    /**
     * Update the post by ID.
     *
     * @param  int   $id
     * @param  array $attributes
     * @return PlaylistEntity
     */
    public function updateById(int $id, array $attributes): PlaylistEntity;

    /**
     * Get the post by ID.
     *
     * @param  int   $id
     * @param  array $filters
     * @return PlaylistEntity
     */
    public function getById(int $id, array $filters = []): PlaylistEntity;

    /**
     * Get the post before ID.
     *
     * @param  int   $id
     * @param  array $filters
     * @return PlaylistEntity
     */
    public function getBeforeId(int $id, array $filters = []): PlaylistEntity;

    /**
     * Get the post after ID.
     *
     * @param  int   $id
     * @param  array $filters
     * @return PlaylistEntity
     */
    public function getAfterId(int $id, array $filters = []): PlaylistEntity;

    /**
     * Paginate over posts.
     *
     * @param  int   $page
     * @param  int   $perPage
     * @param  array $filters
     * @return mixed
     */
    public function paginate(int $page, int $perPage, array $filters = []): LengthAwarePaginator;

    /**
     * Delete the post by ID.
     *
     * @param  int $id
     * @return PlaylistEntity
     */
    public function deleteById(int $id): PlaylistEntity;
}
