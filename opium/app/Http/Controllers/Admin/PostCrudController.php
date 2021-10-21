<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Auth;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->setView();
        $this->crud->addFilter(
            [
                'type' => 'text',
                'name' => 'title',
                'label' => 'Title'
            ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'title', 'like', '%' . $value . '%');
            }
        );
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);

        $this->crud->addFields([
            [
                'label' => 'Category',
                'type' => 'select2',
                'name' => 'category_id',
                'entity' => 'categories',
                'model' => 'App\Models\Category',
                'attribute' => 'name'
            ],
            [
                'name' => 'user_id',
                'type' => 'hidden',
                'value' => backpack_user()->id,
            ]
        ]);
        CRUD::field('title');
        CRUD::addField([   // Wysiwyg
            'name'  => 'description',
            'label' => 'Description',
            'type'  => 'wysiwyg',
        ]);
        $this->crud->addField([
            'name' => 'photo',
            'label' => 'Photo',
            'type' => 'image',
            'upload' => true,
            'prefix'    => 'storage'
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
        $this->setView();
    }

    function setView()
    {
        CRUD::column('title');
        CRUD::addColumn([   // Wysiwyg
            'name'  => 'description',
            'label' => 'Description',
            'type'  => 'model_function',
            'function_name' => 'escapeHtml'
        ]);
        CRUD::addColumn([
            'label'     => 'Category', // Table column heading
            'type'      => 'select',
            'name'      => 'category_id', // the column that contains the ID of that connected entity;
            'entity'    => 'categories', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => 'App\Models\Category', // foreign key model,
        ]);
        CRUD::addColumn([
            'label'     => 'Author', // Table column heading
            'type'      => 'select',
            'name'      => 'user_id', // the column that contains the ID of that connected entity;
            'entity'    => 'author', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model'     => 'App\User', // foreign key model,
        ]);
        CRUD::addColumn([
            'name' => 'photo',
            'label' => 'Photo',
            'type' => 'image',
            'prefix'    => 'storage'
        ]);
    }
}
