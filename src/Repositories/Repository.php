<?php
namespace MediaManager\Repositories;

use MediaManager\Repositories\Contracts\RepositoryInterface;
use MediaManager\Models\Model;
use Illuminate\Container\Container as App;

abstract class Repository implements RepositoryInterface {

    private $app;

    protected $model;

    /*
        Constructor
    */ 

    public function __construct(App $app) {
        $this->app = $app;
        $this->makeModel();
    }

    abstract function model();

    /*
    Get all boards associated with the user
    */

    public function all() {
        return $this->model->all();
    }

    public function get($id) {
        return $this->find($id);
    }

    public function find($id) {
        return $this->model->find($id);
    }

    public function create(array $data) {
        return $this->model->create($data);
    }

    public function update($id, array $data) {
        return $this->model->where('id', '=', $id)->update($data);
    }

    public function delete($board) {
        return $this->model->delete($board);
    }

    public function makeModel() {
        $model = $this->app->make($this->model());
        if(!$model instanceof Model) {
            // Throw a a repository exception
            return 'error';
        }

        return $this->model = $model;
    }

    /**
     * Returns all paginated items.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginated()
    {
        $model = $this->model;

        if (isset(request()->dir) && isset(request()->field)) {
            $model = $model->orderBy(request()->field, request()->dir);
        } else {
            $model = $model->orderBy('created_at', 'desc');
        }

        return $model->paginate(config('cms.pagination', 25));
    }

    /**
     * Stores Widgets into database.
     *
     * @param array $payload
     *
     * @return Widgets
     */
    public function store($payload)
    {
        return $this->model->create($payload);
    }

}