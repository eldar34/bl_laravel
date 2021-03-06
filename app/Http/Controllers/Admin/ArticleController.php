<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Category;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.articles.index', [
            'articles' => Article::orderBy('created_at', 'desc')->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.articles.create', [
            'article'    => [],
            'categories' => Category::with('children')->where('parent_id', 0)->get(),
            'delimiter'  => ''
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputId = (int) $request->input('created_by');
        if($inputId === 1)
        {
            $article = Article::create($request->all());

            // Categories
            if ($request->input('categories')) :
                $article->categories()->attach($request->input('categories'));
            endif;

            return redirect()->route('admin.article.index');
        }else{
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {

        return view('admin.articles.edit', [
            'article'    => $article,
            'categories' => Category::with('children')->where('parent_id', 0)->get(),
            'delimiter'  => ''
        ]);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $inputId = (int) $request->input('modified_by');
        if($inputId === 1)
        {
            $article->update($request->except('slug'));

            // Categories
            $article->categories()->detach();
            if($request->input('categories')) :
                $article->categories()->attach($request->input('categories'));
            endif;

            return redirect()->route('admin.article.index');
        }else{
            abort(404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Article $article)
    {
        $inputId = (int) $request->input('created_by');
        if($inputId === 1)
        {
            $article->categories()->detach();
            $article->delete();

            return redirect()->route('admin.article.index');
        }else{
            abort(404);
        }
    }
}