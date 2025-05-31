<?php

namespace App\Controllers;

use App\Models\MProductModel;
use CodeIgniter\Controller;

class ProductController extends Controller
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new MProductModel();
    }

    // List produk
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $products = $this->productModel->findAll();

        return view('product/index', [
            'products' => $products
        ]);
    }

    // Tampilkan form tambah produk
    public function add()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        return view('product/add');
    }

    // Proses tambah produk
    public function store()
    {
        $validationRules = [
            'txtProductName' => [
                'label' => 'Nama Produk',
                'rules' => 'required|max_length[255]',
            ],
            'txtProductDescription' => [
                'label' => 'Deskripsi Produk',
                'rules' => 'permit_empty',
            ],
            'bitActive' => [
                'label' => 'Status Aktif',
                'rules' => 'required|in_list[0,1]',
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->to('/product/add')->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'txtProductName' => $this->request->getPost('txtProductName'),
            'txtProductDescription' => $this->request->getPost('txtProductDescription'),
            'bitActive' => $this->request->getPost('bitActive'),
            'txtCreatedBy' => session()->get('userName'),
            'dtmCreatedDate' => date('Y-m-d H:i:s'),
        ];

        if (!$this->productModel->insert($data)) {
            session()->setFlashdata('error', 'Gagal menambah produk.');
            return redirect()->to('/product/add');
        }

        session()->setFlashdata('success', 'Produk berhasil ditambahkan!');
        return redirect()->to('/product');
    }

    // Tampilkan detail produk
    public function view($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access this page.');
        }

        $product = $this->productModel->find($id);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Produk dengan ID $id tidak ditemukan");
        }

        return view('product/view', [
            'product' => $product,
            'pageTitle' => 'Detail Produk',
            'cardTitle' => 'Produk',
            'icon' => 'box'
        ]);
    }

    // Tampilkan form edit produk
    public function edit($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan');
        }

        return view('product/edit', [
            'product' => $product
        ]);
    }

    // Proses update produk
    public function update($id = null)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan');
        }

        $validationRules = [
            'txtProductName' => [
                'label' => 'Nama Produk',
                'rules' => 'required|max_length[255]',
            ],
            'txtProductDescription' => [
                'label' => 'Deskripsi Produk',
                'rules' => 'permit_empty',
            ],
            'bitActive' => [
                'label' => 'Status Aktif',
                'rules' => 'required|in_list[0,1]',
            ],
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->to('/product/edit/' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'txtProductName' => $this->request->getPost('txtProductName'),
            'txtProductDescription' => $this->request->getPost('txtProductDescription'),
            'bitActive' => $this->request->getPost('bitActive'),
            'txtLastUpdatedBy' => session()->get('userName'),
            'dtmLastUpdatedDate' => date('Y-m-d H:i:s'),
        ];

        if (!$this->productModel->update($id, $data)) {
            session()->setFlashdata('error', 'Gagal update produk.');
            return redirect()->to('/product/edit/' . $id);
        }

        session()->setFlashdata('success', 'Produk berhasil diupdate!');
        return redirect()->to('/product');
    }

    // Hapus produk
    public function delete($id)
    {
        $product = $this->productModel->find($id);

        if (!$product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan');
        }

        if (!$this->productModel->delete($id)) {
            session()->setFlashdata('error', 'Gagal menghapus produk.');
            return redirect()->to('/product');
        }

        session()->setFlashdata('success', 'Produk berhasil dihapus!');
        return redirect()->to('/product');
    }
}