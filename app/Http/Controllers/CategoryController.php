<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children', 'parent')->get();
        $categoriesWithNoParent = $categories->whereNull('parent_id');
        return view('admin.category.index', compact('categories', 'categoriesWithNoParent'));
    }

    public function store(StoreCategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            if ($request->parent_id) {
                $parentCategory = Category::find($request->parent_id);
                if ($parentCategory->parent_id !== null) {
                    return redirect()->back()->with('error', 'Cannot select parent category because it is already a child of another category.');
                }
            }

            Category::create([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
                'status' => $request->status,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Added category successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add category: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categoriesWithNoParent = Category::whereNull('parent_id')->get();

        return view('admin.category.edit', compact('category', 'categoriesWithNoParent'));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);

            if ($category->children()->count() > 0 && $request->parent_id) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Cannot change parent category because it has child categories.');
            }

            if ($request->parent_id) {
                $parentCategory = Category::find($request->parent_id);
                if (!$parentCategory || $parentCategory->parent_id !== null) {
                    DB::rollBack();
                    return back()->with('error', 'Cannot select parent category because it is already a child of another category.');
                }
            }

            $category->update($request->only(['name', 'parent_id', 'status']));

            DB::commit();
            return redirect()->route('admin.category.index')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);

            if ($category->children()->count() > 0) {
                DB::rollBack();
                return redirect()->route('admin.category.index')->with('error', 'Cannot delete category with child categories.');
            }

            $category->status = $category->status === 'active' ? 'inactive' : 'active';
            $category->save();

            DB::commit();
            return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
