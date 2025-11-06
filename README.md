# SierraTecnologia MediaManager

**SierraTecnologia MediaManager** Various functionality, and basic controller included out-of-the-box.

[![Packagist](https://img.shields.io/packagist/v/sierratecnologia/media-manager.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/sierratecnologia/media-manager)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/sierratecnologia/media-manager.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/sierratecnologia/media-manager/)
[![Travis](https://img.shields.io/travis/sierratecnologia/media-manager.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/sierratecnologia/media-manager)
[![StyleCI](https://styleci.io/repos/60968880/shield)](https://styleci.io/repos/60968880)
[![License](https://img.shields.io/packagist/l/sierratecnologia/media-manager.svg?label=License&style=flat-square)](https://github.com/sierratecnologia/media-manager/blob/master/LICENSE)
[![Build Status](https://github.com/sierratecnologia/media-manager/workflows/CI%20-%20Quality%20%26%20Tests/badge.svg)](https://github.com/sierratecnologia/media-manager/actions)

---

## 📚 Índice

- [Introdução](#-introdução)
- [Instalação](#-instalação)
- [Arquitetura e Estrutura Interna](#-arquitetura-e-estrutura-interna)
- [Principais Funcionalidades](#-principais-funcionalidades)
- [Uso Prático](#-uso-prático)
- [Integração com o Ecossistema SierraTecnologia](#-integração-com-o-ecossistema-sierratecnologia)
- [Extensão e Customização](#-extensão-e-customização)
- [Exemplos Reais](#-exemplos-reais)
- [Guia de Contribuição](#-guia-de-contribuição)
- [Suporte](#-suporte)
- [Segurança](#-segurança)

---

## 🎯 Introdução

### O que é o MediaManager?

O **MediaManager** é uma biblioteca Laravel robusta e extensível desenvolvida pela **SierraTecnologia** para gerenciamento completo de mídias (imagens, vídeos, arquivos e documentos). Ele fornece uma camada de abstração poderosa sobre o sistema de armazenamento do Laravel, facilitando upload, manipulação, versionamento e recuperação de arquivos multimídia.

### Objetivo e Filosofia

O MediaManager foi concebido com os seguintes princípios:

- **Abstração de Storage**: Suporte nativo para múltiplos drivers (local, S3, Google Drive, Dropbox, SFTP)
- **Processamento Inteligente**: Manipulação automática de imagens (resize, crop, thumbnails) via Intervention Image
- **Segurança**: Criptografia de URLs e controle de acesso a arquivos sensíveis
- **Padronização**: Interface consistente para gerenciamento de mídias em todo o ecossistema SierraTecnologia
- **Performance**: Cache inteligente e otimização de armazenamento
- **Rastreabilidade**: Integração com sistema de tracking e auditoria

### Benefícios

✅ **Reutilização de Código**: Uma única API para gerenciar mídias em múltiplos projetos
✅ **Segurança**: URLs criptografadas e validação de tipos de arquivo
✅ **Escalabilidade**: Suporte a CDNs e armazenamento distribuído
✅ **Produtividade**: Helpers e facades para operações comuns
✅ **Manutenibilidade**: Código organizado seguindo padrões SOLID e PSR-12

### Contexto no Ecossistema SierraTecnologia

O MediaManager é parte fundamental do **ecossistema SierraTecnologia / Rica Soluções**, integrando-se com:

- **CMS SierraTecnologia**: Gerenciamento de conteúdo e assets
- **Market**: Upload de produtos e imagens de catálogo
- **Arquiteto**: Documentação técnica e diagramas
- **Locaravel**: Sistema de localização com mídias geolocalizadas

---

## 📦 Instalação

### Requisitos Mínimos

- **PHP**: `^7.2 | ^8.0 | ^8.1 | ^8.2 | ^8.3`
- **Laravel**: `^7.0 | ^8.0 | ^9.0 | ^10.0 | ^11.0`
- **Extensões PHP**: `gd`, `exif`, `imagick` (recomendado), `fileinfo`

### Instalação via Composer

```bash
composer require sierratecnologia/media-manager
```

### Publicação de Configurações

```bash
# Publicar configurações do MediaManager
php artisan vendor:publish --provider="MediaManager\MediaManagerProvider" --tag=config

# Publicar views (opcional)
php artisan vendor:publish --provider="MediaManager\MediaManagerProvider" --tag=views

# Publicar migrations
php artisan vendor:publish --provider="MediaManager\MediaManagerProvider" --tag=migrations

# Executar migrations
php artisan migrate
```

### Registro de Service Providers

O MediaManager utiliza **auto-discovery** do Laravel. Se necessário, registre manualmente em `config/app.php`:

```php
'providers' => [
    // ...
    MediaManager\MediaManagerProvider::class,
],

'aliases' => [
    // ...
    'MediaManager' => MediaManager\Facades\MediaManager::class,
    'FileService' => MediaManager\Services\FileService::class,
],
```

### Configuração de Drivers de Storage

Configure os drivers desejados em `config/filesystems.php`:

```php
'disks' => [
    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
    ],

    'google' => [
        'driver' => 'google',
        'clientId' => env('GOOGLE_DRIVE_CLIENT_ID'),
        'clientSecret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
        'refreshToken' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
        'folderId' => env('GOOGLE_DRIVE_FOLDER_ID'),
    ],
],
```

---

## 🏗️ Arquitetura e Estrutura Interna

### Organização de Pastas e Namespaces

```
src/
├── Builders/          # Construtores de objetos complexos (Photos, Thumbnails)
├── Console/           # Comandos Artisan
├── Contracts/         # Interfaces e contratos
├── DataTables/        # Integração com DataTables
├── Elements/          # Componentes de processamento (VideoEncoder)
├── Entities/          # Entidades de domínio e DTOs
│   └── Uploads/       # Serviços de upload (Image, Attachment)
├── Events/            # Eventos do sistema
├── Exception/         # Exceções customizadas
├── Facades/           # Facades Laravel
├── Http/              # Camada HTTP
│   ├── Api/           # Controllers API
│   ├── Controllers/   # Controllers Web
│   ├── Policies/      # Policies de autorização
│   ├── Requests/      # Form Requests
│   └── Resources/     # API Resources
├── Managers/          # Gestores de alto nível
├── Models/            # Eloquent Models
├── Observers/         # Eloquent Observers
├── Providers/         # Service Providers customizados
├── Repositories/      # Camada de repositórios
├── Services/          # Serviços de negócio
└── Utils/             # Utilitários e helpers
```

### Padrões Adotados

#### 1. Repository Pattern

Abstração da camada de acesso a dados:

```php
// src/Repositories/ImageRepository.php
class ImageRepository
{
    public function find($id)
    {
        return Image::find($id);
    }

    public function saveFromUpload($file, $directory = 'images')
    {
        // Lógica de persistência
    }
}
```

#### 2. Service Layer

Lógica de negócio isolada:

```php
// src/Services/FileService.php
class FileService
{
    public static function saveFile($fileName, $directory = '', $fileTypes = [])
    {
        // Validação, processamento e armazenamento
    }
}
```

#### 3. Event-Driven Architecture

Eventos para desacoplamento:

```php
// src/Events/MediaFileAdded.php
class MediaFileAdded
{
    public $media;

    public function __construct($media)
    {
        $this->media = $media;
    }
}
```

#### 4. Observer Pattern

Observers Eloquent para side-effects:

```php
// src/Observers/MediaObserver.php
class MediaObserver
{
    public function created(Media $media)
    {
        // Gerar thumbnails, processar metadata, etc.
    }
}
```

### Comunicação entre Camadas

```
┌─────────────────┐
│   Controller    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Form Request   │ ◄── Validação
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│    Service      │ ◄── Lógica de Negócio
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   Repository    │ ◄── Persistência
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│     Model       │ ◄── Eloquent ORM
└─────────────────┘
```

### Convenções e Boas Práticas

- **PSR-12**: Padrão de codificação
- **Type Hints**: Declaração de tipos em métodos e propriedades
- **Docblocks**: Documentação inline para IDEs
- **Dependency Injection**: Injeção de dependências via Container
- **Single Responsibility**: Cada classe tem uma responsabilidade única

---

## ⚙️ Principais Funcionalidades

### 1. Upload e Manipulação de Arquivos

#### Upload Simples

```php
use MediaManager\Services\FileService;

$result = FileService::saveFile('documento', 'uploads/documentos', ['pdf', 'docx']);

// Retorna:
// [
//     'original' => 'contrato.pdf',
//     'name' => 'uploads/documentos/a3f8b9c2d1e4f5a6b7c8d9e0f1a2b3c4.pdf'
// ]
```

#### Upload de Clones (arquivos existentes)

```php
$result = FileService::saveClone('/path/to/file.jpg', 'clones/', ['jpg', 'png']);
```

### 2. Gerenciamento de Imagens

#### Upload e Processamento Automático

```php
use MediaManager\Entities\Uploads\ImageService;

$imageService = app(ImageService::class);

$image = $imageService->save(
    request()->file('imagem'),
    'produtos/',
    [
        'resize' => ['width' => 1200, 'height' => 800],
        'thumbnail' => true,
        'watermark' => true,
    ]
);
```

#### Geração de Thumbnails

```php
use MediaManager\Builders\ThumbnailBuilder;

$thumbnail = ThumbnailBuilder::create()
    ->forMedia($media)
    ->withDimensions(300, 200)
    ->withQuality(85)
    ->build();
```

#### Manipulação com Intervention Image

```php
use MediaManager\Services\ImagineImageProcessor;

$processor = app(ImagineImageProcessor::class);

$processedImage = $processor->resize($imagePath, 800, 600);
$croppedImage = $processor->crop($imagePath, 400, 400, 'center');
$watermarked = $processor->watermark($imagePath, $watermarkPath, 'bottom-right');
```

### 3. Integração com Drivers de Armazenamento

#### Configuração Multi-Driver

```php
// Salvar em S3
Storage::disk('s3')->put($path, $contents);

// Salvar em Google Drive
Storage::disk('google')->put($path, $contents);

// Salvar em Dropbox
Storage::disk('dropbox')->put($path, $contents);
```

#### Sincronização entre Disks

```php
$localPath = 'uploads/video.mp4';
$s3Path = 'videos/video.mp4';

// Copiar de local para S3
Storage::disk('s3')->put(
    $s3Path,
    Storage::disk('local')->get($localPath)
);
```

### 4. Controle de Permissões e Autenticação

#### URLs Criptografadas

```php
use MediaManager\Services\FileService;

// URL pública protegida
$publicUrl = FileService::fileAsPublicAsset('private/documento.pdf');
// Retorna: /public-asset/eyJpdiI6IkR...

// URL de download
$downloadUrl = FileService::fileAsDownload('private/arquivo.zip', 'Arquivo.zip');
// Retorna: /public-download/eyJpdiI6...
```

#### Rotas Protegidas

```php
// routes/web.php
Route::get('/public-asset/{encrypted}', [FilesController::class, 'publicAsset'])
    ->middleware(['auth', 'can:view-media']);

Route::get('/public-download/{encrypted}/{name}', [FilesController::class, 'download'])
    ->middleware(['auth', 'can:download-media']);
```

### 5. Processamento de Vídeo

#### Encoding de Vídeos

```php
use MediaManager\Elements\VideoEncoder;

$encoder = new VideoEncoder();

$encoded = $encoder->encode(
    '/path/to/video.mp4',
    'outputs/encoded.mp4',
    [
        'resolution' => '1280x720',
        'bitrate' => '2000k',
        'codec' => 'h264',
    ]
);
```

### 6. Playlists e Organização

#### Criar Playlist

```php
use MediaManager\Models\Playlist;
use MediaManager\Models\Video;

$playlist = Playlist::create([
    'name' => 'Tutoriais Laravel',
    'description' => 'Série de tutoriais sobre Laravel',
    'is_public' => true,
]);

$playlist->videos()->attach([1, 2, 3, 4]);
```

---

## 💻 Uso Prático

### Exemplo Completo: Upload de Imagem de Produto

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MediaManager\Services\FileService;
use MediaManager\Entities\Uploads\ImageService;
use MediaManager\Models\Media;
use App\Models\Product;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Criar produto
        $product = Product::create([
            'name' => $request->name,
        ]);

        // Upload e processamento da imagem
        $image = $this->imageService->save(
            $request->file('image'),
            "produtos/{$product->id}/",
            [
                'resize' => ['width' => 1200, 'height' => 1200],
                'thumbnail' => true,
                'sizes' => [
                    'small' => [300, 300],
                    'medium' => [600, 600],
                    'large' => [1200, 1200],
                ],
            ]
        );

        // Associar imagem ao produto
        $product->media()->attach($image->id, ['type' => 'main_image']);

        return response()->json([
            'message' => 'Produto criado com sucesso',
            'product' => $product->load('media'),
            'image_url' => $image->getUrl(),
            'thumbnails' => $image->thumbnails->map->getUrl(),
        ], 201);
    }

    public function show(Product $product)
    {
        $mainImage = $product->media()
            ->wherePivot('type', 'main_image')
            ->first();

        return view('products.show', [
            'product' => $product,
            'image' => $mainImage,
            'thumbnails' => $mainImage?->thumbnails ?? collect(),
        ]);
    }
}
```

### Integração com Storage Nativo

```php
use Illuminate\Support\Facades\Storage;
use MediaManager\Models\Media;

// Salvar arquivo no storage configurado
$path = Storage::disk('s3')->put('uploads', $request->file('arquivo'));

// Registrar no MediaManager
$media = Media::create([
    'disk' => 's3',
    'file_name' => basename($path),
    'mime_type' => $request->file('arquivo')->getMimeType(),
    'size' => $request->file('arquivo')->getSize(),
    'collection_name' => 'uploads',
]);

// Recuperar URL pública
$url = Storage::disk('s3')->url($media->file_name);
```

### Helper de Preview de Arquivos

```php
use MediaManager\Services\FileService;

// Em uma view Blade
$previewUrl = FileService::filePreview($media->getDiskPath());

<img src="{{ $previewUrl }}" alt="{{ $media->name }}">
```

### Dicas de Performance e Caching

#### 1. Cache de Imagens

```php
// config/imagecache.php
return [
    'route' => 'imagecache',
    'path' => public_path('imagecache'),
    'lifetime' => 43200, // 30 dias
];

// Uso
<img src="{{ route('imagecache', ['template' => 'medium', 'filename' => $image->path]) }}">
```

#### 2. Lazy Loading de Thumbnails

```php
// Carregar apenas quando necessário
$media = Media::find($id);
$thumbnails = $media->thumbnails()->orderBy('width')->get();
```

#### 3. CDN Integration

```php
// .env
AWS_CLOUDFRONT_URL=https://d111111abcdef8.cloudfront.net

// config/filesystems.php
's3' => [
    'driver' => 's3',
    'url' => env('AWS_CLOUDFRONT_URL'),
    // ...
],
```

---

## 🔗 Integração com o Ecossistema SierraTecnologia

### Relação com Outros Módulos

#### CMS SierraTecnologia

O MediaManager fornece a camada de gerenciamento de assets para o CMS:

```php
// Registrar imagem de post
$post->addMedia($request->file('featured_image'))
     ->toMediaCollection('featured');

// Recuperar
$featuredImage = $post->getFirstMedia('featured');
```

#### Market (E-commerce)

Gerenciamento de imagens de produtos:

```php
$product->addMultipleMediaFromRequest(['gallery'])
        ->each(fn ($fileAdder) => $fileAdder->toMediaCollection('gallery'));
```

#### Locaravel (Geolocalização)

Mídias com metadados de localização:

```php
$media->metadata()->create([
    'key' => 'geolocation',
    'value' => json_encode([
        'lat' => $request->latitude,
        'lng' => $request->longitude,
    ]),
]);
```

### Padrões de Versionamento e CI/CD

O MediaManager segue [Semantic Versioning](https://semver.org/):

- **Major**: Mudanças incompatíveis com versões anteriores
- **Minor**: Novas funcionalidades compatíveis
- **Patch**: Correções de bugs

#### Workflow de CI/CD

O projeto utiliza GitHub Actions para automação:

```yaml
# .github/workflows/ci.yml
name: CI - Quality & Tests

on: [push, pull_request]

jobs:
  tests:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php: [8.0, 8.1, 8.2, 8.3]
        laravel: [8.*, 9.*, 10.*, 11.*]

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2

      - name: Install dependencies
        run: composer install

      - name: Run tests
        run: vendor/bin/phpunit

      - name: Run PHPCS
        run: vendor/bin/phpcs

      - name: Run PHPStan
        run: vendor/bin/phpstan analyse
```

### Padronização entre Microserviços

Todos os microserviços SierraTecnologia utilizam o MediaManager como interface única:

```php
// Padrão de upload em qualquer microserviço
$media = app(ImageService::class)->save(
    $file,
    $directory,
    $options
);

// Garantia de consistência e reutilização
```

---

## 🔧 Extensão e Customização

### Criar Novos Drivers de Armazenamento

#### 1. Criar Adapter

```php
<?php

namespace App\Storage;

use League\Flysystem\Adapter\AbstractAdapter;

class CloudflareR2Adapter extends AbstractAdapter
{
    protected $client;

    public function __construct($config)
    {
        $this->client = new R2Client($config);
    }

    public function write($path, $contents, Config $config)
    {
        return $this->client->upload($path, $contents);
    }

    // Implementar outros métodos...
}
```

#### 2. Registrar Service Provider

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use App\Storage\CloudflareR2Adapter;

class CloudflareR2ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('r2', function ($app, $config) {
            return new CloudflareR2Adapter($config);
        });
    }
}
```

#### 3. Configurar em `config/filesystems.php`

```php
'disks' => [
    'r2' => [
        'driver' => 'r2',
        'account_id' => env('R2_ACCOUNT_ID'),
        'access_key_id' => env('R2_ACCESS_KEY_ID'),
        'secret_access_key' => env('R2_SECRET_ACCESS_KEY'),
        'bucket' => env('R2_BUCKET'),
    ],
],
```

### Interceptar Uploads e Aplicar Regras Customizadas

#### Criar Middleware de Upload

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\UploadedFile;

class ValidateImageDimensions
{
    public function handle($request, Closure $next)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            if ($image instanceof UploadedFile) {
                [$width, $height] = getimagesize($image->path());

                if ($width < 800 || $height < 600) {
                    return response()->json([
                        'error' => 'Imagem deve ter no mínimo 800x600 pixels'
                    ], 422);
                }
            }
        }

        return $next($request);
    }
}
```

#### Aplicar em Rotas

```php
Route::post('/upload', [UploadController::class, 'store'])
     ->middleware(['auth', ValidateImageDimensions::class]);
```

### Criar Eventos e Listeners Customizados

#### Evento Customizado

```php
<?php

namespace App\Events;

use MediaManager\Models\Media;

class ImageProcessed
{
    public $media;
    public $thumbnails;

    public function __construct(Media $media, array $thumbnails)
    {
        $this->media = $media;
        $this->thumbnails = $thumbnails;
    }
}
```

#### Listener

```php
<?php

namespace App\Listeners;

use App\Events\ImageProcessed;
use Illuminate\Support\Facades\Cache;

class CacheImageMetadata
{
    public function handle(ImageProcessed $event)
    {
        Cache::put(
            "media:{$event->media->id}:metadata",
            [
                'thumbnails' => $event->thumbnails,
                'url' => $event->media->getUrl(),
            ],
            now()->addDays(7)
        );
    }
}
```

#### Registrar em EventServiceProvider

```php
protected $listen = [
    ImageProcessed::class => [
        CacheImageMetadata::class,
        NotifyAdminOfNewImage::class,
    ],
];
```

### Estender Models com Traits Customizados

```php
<?php

namespace App\Traits;

use MediaManager\Models\Media;

trait HasGallery
{
    public function gallery()
    {
        return $this->morphMany(Media::class, 'model')
                    ->where('collection_name', 'gallery')
                    ->orderBy('order_column');
    }

    public function addToGallery($file, $order = 0)
    {
        return $this->gallery()->create([
            'file_name' => $file,
            'order_column' => $order,
        ]);
    }
}

// Uso
class Product extends Model
{
    use HasGallery;
}

$product->addToGallery($uploadedFile);
```

---

## 🌟 Exemplos Reais

### Caso de Uso 1: CMS com Galeria de Imagens

**Antes do MediaManager:**

```php
// Upload manual, sem padronização
$path = $request->file('image')->store('public/images');
$url = Storage::url($path);

// Problemas:
// - Sem thumbnails automáticos
// - Sem validação consistente
// - Sem controle de acesso
// - Difícil de escalar para S3/CDN
```

**Depois do MediaManager:**

```php
use MediaManager\Entities\Uploads\ImageService;

$imageService = app(ImageService::class);

$media = $imageService->save(
    $request->file('image'),
    'cms/posts/',
    [
        'thumbnail' => true,
        'sizes' => ['small' => [150, 150], 'medium' => [600, 400]],
    ]
);

$post->media()->attach($media->id);

// Benefícios:
// ✅ Thumbnails automáticos
// ✅ Validação embutida
// ✅ URLs criptografadas
// ✅ Suporte a múltiplos drivers
// ✅ Cache de imagens
```

### Caso de Uso 2: E-commerce com Upload de Produtos

**Cenário:** Sistema Market com milhares de produtos

```php
<?php

namespace Market\Http\Controllers;

use Market\Models\Product;
use MediaManager\Entities\Uploads\ImageService;

class ProductImageController extends Controller
{
    public function uploadGallery(Request $request, Product $product)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $imageService = app(ImageService::class);
        $uploadedMedia = [];

        foreach ($request->file('images') as $index => $image) {
            $media = $imageService->save(
                $image,
                "produtos/{$product->id}/gallery/",
                [
                    'resize' => ['width' => 1500, 'height' => 1500],
                    'sizes' => [
                        'thumb' => [150, 150],
                        'catalog' => [400, 400],
                        'zoom' => [1500, 1500],
                    ],
                ]
            );

            $product->media()->attach($media->id, [
                'collection' => 'gallery',
                'order' => $index,
            ]);

            $uploadedMedia[] = $media;
        }

        return response()->json([
            'message' => 'Galeria atualizada com sucesso',
            'images' => $uploadedMedia,
        ]);
    }
}
```

**Resultado:**
- 🚀 Upload de múltiplas imagens otimizado
- 📸 Thumbnails em 3 tamanhos para diferentes contextos
- 🔒 URLs protegidas e criptografadas
- ⚡ Pronto para CDN/S3

### Caso de Uso 3: Sistema de Documentos com Auditoria

```php
use MediaManager\Models\File;
use MediaManager\Services\FileService;
use Tracking\Services\TrackingService;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        $result = FileService::saveFile(
            $request->file('documento'),
            'documentos/juridico/',
            ['pdf', 'docx', 'xlsx']
        );

        $file = File::create([
            'name' => $result['original'],
            'path' => $result['name'],
            'user_id' => auth()->id(),
            'department' => 'juridico',
        ]);

        // Rastreamento com Tracking
        TrackingService::track('document.uploaded', [
            'file_id' => $file->id,
            'filename' => $result['original'],
            'user' => auth()->user()->email,
        ]);

        // URL segura de download
        $downloadUrl = FileService::fileAsDownload(
            $result['name'],
            $result['original']
        );

        return response()->json([
            'file' => $file,
            'download_url' => $downloadUrl,
        ]);
    }
}
```

**Benefícios Obtidos:**
- ✅ Controle total de acesso a documentos sensíveis
- ✅ Auditoria completa de uploads e downloads
- ✅ URLs com expiração (via criptografia)
- ✅ Integração com sistema de tracking

---

## 🤝 Guia de Contribuição

### Como Contribuir

Agradecemos seu interesse em contribuir! Siga as etapas:

1. **Fork** o repositório
2. **Clone** seu fork: `git clone https://github.com/seu-usuario/media-manager.git`
3. **Crie uma branch** para sua feature: `git checkout -b feature/nova-funcionalidade`
4. **Faça suas alterações** seguindo os padrões de código
5. **Execute os testes**: `vendor/bin/phpunit`
6. **Execute as verificações de qualidade**:
   ```bash
   vendor/bin/phpcs --standard=PSR12 src/
   vendor/bin/phpstan analyse src/
   vendor/bin/phpmd src/ text phpmd.xml
   ```
7. **Commit** suas mudanças: `git commit -m "feat: adiciona nova funcionalidade X"`
8. **Push** para seu fork: `git push origin feature/nova-funcionalidade`
9. **Abra um Pull Request** descrevendo suas alterações

### Padrões de Commits

Seguimos [Conventional Commits](https://www.conventionalcommits.org/):

```
feat: adiciona suporte a Cloudflare R2
fix: corrige upload de arquivos > 5MB
docs: atualiza documentação de instalação
style: formata código segundo PSR-12
refactor: reorganiza estrutura de pastas
test: adiciona testes para ImageService
chore: atualiza dependências
```

### Branch Naming

- `feature/nome-da-feature` - Novas funcionalidades
- `fix/descricao-do-bug` - Correções de bugs
- `docs/atualizacao` - Atualizações de documentação
- `refactor/nome-do-refactor` - Refatorações

### Execução Local das Ferramentas de Qualidade

#### PHPUnit (Testes)

```bash
# Executar todos os testes
vendor/bin/phpunit

# Com coverage
vendor/bin/phpunit --coverage-html coverage/

# Testes específicos
vendor/bin/phpunit --filter ImageServiceTest
```

#### PHPCS (Code Sniffer - PSR-12)

```bash
# Verificar padrão de código
vendor/bin/phpcs --standard=phpcs.xml src/

# Corrigir automaticamente
vendor/bin/phpcbf --standard=phpcs.xml src/
```

#### PHPStan (Análise Estática)

```bash
# Análise nível 5 (padrão do projeto)
vendor/bin/phpstan analyse src/

# Análise nível máximo (8)
vendor/bin/phpstan analyse src/ --level=8
```

#### PHPMD (Mess Detector)

```bash
# Detectar problemas de design
vendor/bin/phpmd src/ text phpmd.xml

# Gerar relatório HTML
vendor/bin/phpmd src/ html phpmd.xml --reportfile phpmd-report.html
```

#### Psalm (Análise de Tipos)

```bash
# Análise de tipos
vendor/bin/psalm

# Análise com relatório
vendor/bin/psalm --report=psalm-report.txt
```

#### GrumPHP (Git Hooks)

O projeto utiliza GrumPHP para validações automáticas antes de commits:

```bash
# Executar manualmente
vendor/bin/grumphp run

# Testar commit
git commit -m "feat: teste de commit"
```

### Política de Licença

O MediaManager é open-source licenciado sob [MIT License](LICENSE).

### Contato da Equipe Técnica

- **Email**: help@sierratecnologia.com.br
- **Slack**: [SierraTecnologia Workspace](https://bit.ly/sierratecnologia-slack)
- **Issues**: [GitHub Issues](https://github.com/sierratecnologia/media-manager/issues)

---

## 📞 Suporte

Canais de suporte disponíveis:

- 💬 [Chat no Slack](https://bit.ly/sierratecnologia-slack)
- 📧 [Email de Ajuda](mailto:help@sierratecnologia.com.br)
- 🐦 [Twitter @sierratecnologia](https://twitter.com/sierratecnologia)
- 📚 [Documentação Completa](https://sierratecnologia.com/packages/media-manager/)

---

## 🔒 Segurança

Se você descobrir uma vulnerabilidade de segurança, por favor **NÃO** abra uma issue pública.

Envie um email para [help@sierratecnologia.com.br](mailto:help@sierratecnologia.com.br) com:

- Descrição detalhada da vulnerabilidade
- Passos para reproduzir
- Impacto potencial
- Sugestões de correção (se houver)

Todas as vulnerabilidades serão tratadas com prioridade máxima.

---

## 📝 Changelog

Consulte o [Changelog](CHANGELOG.md) para histórico completo de versões.

---


## About SierraTecnologia

SierraTecnologia is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Rio de Janeiro, Brazil since June 2008. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2008-2020 SierraTecnologia, Some rights reserved.

https://github.com/sampotts/plyr

        "bkwld/croppa": "^4.0",