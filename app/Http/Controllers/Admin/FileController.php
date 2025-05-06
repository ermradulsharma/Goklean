<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class FileController extends Controller
{
    /**
     * Access to File Manager
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return view('file-manager');
    }

    /**
     * File manager CKEditor
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function ckeditor()
    {
        return view('vendor.file-manager.ckeditor');
    }

    /**
     * File manager Button
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function button()
    {
        return view('vendor.file-manager.fmButton');
    }
}
