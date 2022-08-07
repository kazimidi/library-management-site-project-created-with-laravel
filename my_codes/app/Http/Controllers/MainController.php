<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home() {
        $categories = Category::all();
        $books = Book::orderBy('id', 'DESC')->paginate(5);
        return view('main_views.home', ['categories' => $categories, 'books' => $books]);
    }

    public function single_book($book) {
        $book = Book::find($book);
        if(empty($book)) {
            abort(404);
        }

        return view('main_views.single_book', ['book' => $book]);
    }

    public function books_for_category(Category $category) {
        $books = $category->books()->paginate(5);
        $categories = Category::all();
        return view('main_views.books_for_category', ['books' => $books, 'categories' => $categories, 'category' => $category]);
    }

    public function search() {
        $books = Book::where('name', 'like', '%' . $_GET['searched'] . '%')->orWhere('short_description', 'like', '%' . $_GET['searched'] . '%')->orWhere('long_description', 'like', '%' . $_GET['searched'] . '%')->paginate(5);
        $categories = Category::all();
        return view('main_views.search', ['books' => $books, 'categories' => $categories]);
    }

    public function login_page() {
        if(auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }
}
