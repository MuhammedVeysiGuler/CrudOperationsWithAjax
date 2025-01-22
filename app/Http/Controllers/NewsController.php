<?php

namespace App\Http\Controllers;

use App\Interfaces\NewsServiceInterface;
use Illuminate\Http\Request;

class NewsController extends BaseController
{
    protected $newsService;

    public function __construct(NewsServiceInterface $service)
    {
        parent::__construct($service);
        $this->newsService = $service;
    }

    public function index()
    {
        return view('panel.news.index');
    }

    public function fetch(Request $request)
    {
        return $this->newsService->getDataTable();
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'city' => 'required',
            'mail' => 'required|email'
        ]);

        $data = [
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'city' => $validated['city'],
            'email' => $validated['mail']
        ];

        $this->newsService->create($data);
        return response()->json(['Success' => 'success']);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:news,id'
        ]);

        try {
            $this->newsService->delete($request->id);
            return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting record'], 500);
        }
    }

    public function get(Request $request)
    {
        $news = $this->newsService->findById($request->id);
        return response()->json([
            'title' => $news->title,
            'author' => $news->author,
            'content' => $news->content,
            'is_published' => $news->is_published
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'author' => 'required',
            'content' => 'required',
            'is_published' => 'boolean'
        ]);

        $this->newsService->update($id, $validated);
        return response()->json(['success' => true, 'message' => 'News updated successfully']);
    }

    public function update_view($id)
    {
        $newsItem = $this->newsService->findById($id);
        return view('panel.news.update', compact('newsItem', 'id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'author' => 'required',
            'content' => 'required',
            'is_published' => 'boolean'
        ]);

        $news = $this->newsService->create($validated);
        return response()->json(['success' => true, 'message' => 'News created successfully']);
    }

//---------------------


}
