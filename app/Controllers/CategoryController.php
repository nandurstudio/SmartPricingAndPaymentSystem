<?php

namespace App\Controllers;

use App\Models\MCategoryModel;
use CodeIgniter\Controller;

class CategoryController extends Controller
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new MCategoryModel();
    }

    // List all categories
    public function index()
    {
        $categories = $this->categoryModel->findAll();
        return view('category/index', [
            'categories' => $categories
        ]);
    }

    // Show add form
    public function add()
    {
        return view('category/add');
    }

    // Store new category
    public function store()
    {
        $validationRules = [
            'txtCategoryName' => [
                'label' => 'Category Name',
                'rules' => 'required|max_length[100]',
            ],
            'bitActive' => [
                'label' => 'Active Status',
                'rules' => 'required|in_list[0,1]',
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->to('/category/add')->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'txtCategoryName' => $this->request->getPost('txtCategoryName'),
            'bitActive' => $this->request->getPost('bitActive'),
            'txtCreatedBy' => session()->get('userName'),
            'dtmCreatedDate' => date('Y-m-d H:i:s'),
        ];

        $this->categoryModel->insert($data);

        session()->setFlashdata('success', 'Category added successfully!');
        return redirect()->to('/category');
    }

    // Show edit form
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }
        return view('category/edit', [
            'category' => $category
        ]);
    }

    // Update category
    public function update($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }

        $validationRules = [
            'txtCategoryName' => [
                'label' => 'Category Name',
                'rules' => 'required|max_length[100]',
            ],
            'bitActive' => [
                'label' => 'Active Status',
                'rules' => 'required|in_list[0,1]',
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->to('/category/edit/' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'txtCategoryName' => $this->request->getPost('txtCategoryName'),
            'bitActive' => $this->request->getPost('bitActive'),
            'txtLastUpdatedBy' => session()->get('userName'),
            'dtmLastUpdatedDate' => date('Y-m-d H:i:s'),
        ];

        $this->categoryModel->update($id, $data);

        session()->setFlashdata('success', 'Category updated successfully!');
        return redirect()->to('/category');
    }

    // Delete category
    public function delete($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }

        $this->categoryModel->delete($id);

        session()->setFlashdata('success', 'Category deleted successfully!');
        return redirect()->to('/category');
    }
}