<?php

namespace Modules\TemplateConfiguration\Http\Controllers;

use App\Models\CentralUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\VisibilityLevel\Models\VisibilityLevel;
use Modules\TemplateConfiguration\Models\TemplateConfiguration;
use Modules\TemplateConfiguration\Http\Requests\StoreTemplateRequest;

class TemplateConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $visibilityLevels = VisibilityLevel::all();
        $templates = TemplateConfiguration::with('admin', 'user', 'visibilityLevels')->where('central_user_id', $user->id)->orWhere('central_admin_id', $user->id)->get();
        if ($request->wantsJson()) {
            return response()->json([
                'templates' => $templates,
            ]);
        }
        // return view('templates.index', compact('templates', 'visibilityLevels'));
    }

    public function create()
    {
        $user = auth()->user();
        $visibilityLevels = VisibilityLevel::all();
        $users = CentralUser::all();
        return view('templates.create', compact('visibilityLevels', 'users', 'user'));
    }

    public function store(StoreTemplateRequest $request, $entity)
    {
        $entity = Str::lower($entity); // Normalize entity type

        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Admin']);
        $filterColumn = $isAdmin ? 'central_admin_id' : 'central_user_id';

        // Convert JSON string to array
        $configData = json_decode($request->configuration, true);

        // Check if a template already exists
        $template = TemplateConfiguration::where('entity_type', $entity)
            ->where('view_type', Str::lower($request->view_type))
            ->where($filterColumn, $user->id)
            ->first();

        if ($template) {
            // Update existing template
            $template->update([
                'configuration' => $configData,
                'is_default' => $request->is_default ?? 0,
            ]);
        } else {
            // Create a new template
            $template = TemplateConfiguration::create([
                'entity_type' => $entity,
                'view_type' => Str::lower($request->view_type),
                'configuration' => $configData,
                'is_default' => $request->is_default ?? 0,
                $filterColumn => $user->id,
            ]);
        }

        // Attach visibility levels if provided
        if ($request->has('visibility_levels')) {
            $template->visibilityLevels()->sync($request->visibility_levels);
        }

        return response()->json([
            'success' => true,
            'message' => $template->wasRecentlyCreated ? 'Template created successfully' : 'Template updated successfully',
            'template' => $template,
        ]);
    }




    public function edit(Request $request, $id)
    {
        $user = auth()->user();
        $template = TemplateConfiguration::with('visibilityLevels')->findOrFail($id);
        $users = CentralUser::all();
        $visibilityLevels = VisibilityLevel::all();

        if ($request->wantsJson()) {
            return response()->json([
                'template' => $template,
            ]);
        }

        return view('templates.edit', compact('template', 'users', 'visibilityLevels', 'user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'entity_type' => 'required|string|max:255',
            'view_type' => 'required|string',
            'configuration' => 'required|json',
            'is_default' => 'nullable|boolean',
            'visibility_levels' => 'nullable|array',
            'visibility_levels.*' => 'exists:visibility_levels,id',
        ]);

        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Admin']);

        DB::beginTransaction();
        try {
            $template = TemplateConfiguration::findOrFail($id);

            // Update template
            $template->update([
                'entity_type' => $request->entity_type,
                'view_type' => $request->view_type,
                'configuration' => json_decode($request->configuration, true) ?: [],
                'is_default' => $request->boolean('is_default'),
            ]);

            // Assign admin_id or user_id based on role
            if ($isAdmin) {
                $template->admin_id = $user->id;
                $template->user_id = null;
            } else {
                $template->user_id = $user->id;
                $template->admin_id = null;
            }
            $template->save();

            // Sync visibility levels
            if ($request->has('visibility_levels')) {
                $template->visibilityLevels()->sync($request->visibility_levels);
            }

            DB::commit();

            // Redirect based on role
            return response()->json([
                'success' => true,
                'redirect' => $isAdmin ? route('templates.index') : route('user.templates.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // public function viewEntityTemplate($id)
    // {
    //     $template = TemplateConfiguration::find($id);
    //     // Get filters related to this entity type
    //     $filters = CustomFilter::where('type', $template->entity_type)->where('user_id', $template->admin_id)->orWhere('user_id', $template->user_id)->get();
    //     return view('templates.show', compact('template', 'filters'));
    // }




    public function destroy(Request $request, $id)
    {
        $template = TemplateConfiguration::find($id);

        $template->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Template Deleted Successfully',
            ]);
        }

        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Admin']);
        if ($isAdmin) {
            return redirect()->route('templates.index')->with('success', 'Template Deleted Successfully');
        } else {
            return redirect()->route('user.templates.index')->with('success', 'Template Deleted Successfully');
        }
    }


    public function updateVisibility(Request $request, $id)
    {
        $request->validate([
            'visibility_id' => 'nullable|exists:visibility_levels,id'
        ]);

        DB::beginTransaction();
        try {
            $template = TemplateConfiguration::findOrFail($id);

            // If visibility level is selected, sync it, otherwise, detach it
            if ($request->filled('visibility_id')) {
                $template->visibilityLevels()->sync([$request->visibility_id]);
            } else {
                $template->visibilityLevels()->detach(); // Remove the visibility level if none is selected
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }


    public function templateView(Request $req, $entityType)
    {
        $user = Auth::user();
        $entityType = strtolower($entityType);

        // Determine the filter column based on the user's role
        // if ($user->hasRole('User')) {
        //     $filterColumn = 'user_id';
        // } elseif ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
        //     $filterColumn = 'admin_id';
        // } else {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            $filterColumn = 'central_admin_id';
        } else {
            $filterColumn = 'central_user_id';
        }

        // Fetch available view types for this entity type and user/admin
        $availableViews = TemplateConfiguration::whereRaw('LOWER(entity_type) = ?', [$entityType])
            ->where($filterColumn, $user->id)
            ->distinct()
            ->pluck('view_type');

        // Check if the user has at least one template configuration
        $userTemplate = TemplateConfiguration::whereRaw('LOWER(entity_type) = ?', [$entityType])
            ->where($filterColumn, $user->id)
            ->orderBy('created_at', 'asc') // Get the first one created
            ->first();

        if ($userTemplate) {
            // If the user has a template, use its view type and configuration
            $defaultViewType = $userTemplate->view_type;
            $templateConfiguration = $userTemplate->configuration;
        } else {
            // If no user template, fallback to default configuration
            $defaultViewType = TemplateConfiguration::whereRaw('LOWER(entity_type) = ?', [$entityType])
                ->where('is_default', true)
                ->value('view_type');

            $template = TemplateConfiguration::whereRaw('LOWER(entity_type) = ?', [$entityType])
                ->whereRaw('LOWER(view_type) = ?', [$defaultViewType])
                ->first();

            $templateConfiguration = $template ? $template->configuration : null;
        }

        return response()->json([
            'entityType' => $entityType,
            'availableViews' => $availableViews, // Show all available views
            'defaultViewType' => $defaultViewType, // First found view type
            'template_configuration' => $templateConfiguration, // Configuration of the first found view
        ]);
    }



    public function loadViewType(Request $request)
    {
        $user = Auth::user();
        $entityType = strtolower($request->entityType);
        $viewType = strtolower($request->viewType);

        // Determine which column to filter by based on the user's role
        // if ($user->hasRole('User')) {
        //     $filterColumn = 'central_user_id';
        // } elseif ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
        //     $filterColumn = 'central_admin_id';
        // } else {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            $filterColumn = 'central_admin_id';
        } else {
            $filterColumn = 'central_user_id';
        }

        // Fetch the template configuration dynamically for the logged-in user
        $template = TemplateConfiguration::whereRaw('LOWER(entity_type) = ?', [$entityType])
            ->whereRaw('LOWER(view_type) = ?', [$viewType])
            ->where($filterColumn, $user->id)
            ->first();

        if (!$template) {
            return response()->json(['error' => 'No configuration found for this view type.'], 404);
        }

        return response()->json([
            'template' => $template->configuration
        ]);
    }

    public function newloadViewType($entity, $view)
    {
        $user = Auth::user();
        $entityType = strtolower($entity);
        $viewType = strtolower($view);

        // Determine which column to filter by based on the user's role
        // if ($user->hasRole('User')) {
        //     $filterColumn = 'user_id';
        // } elseif ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
        //     $filterColumn = 'admin_id';
        // } else {
        //     return response()->json(['error' => 'Unauthorized access'], 403);
        // }

        if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
            $filterColumn = 'admin_id';
        } else {
            $filterColumn = 'user_id';
        }

        // Fetch the template configuration dynamically for the logged-in user
        $template = TemplateConfiguration::whereRaw('LOWER(entity_type) = ?', [$entityType])
            ->whereRaw('LOWER(view_type) = ?', [$viewType])
            ->where($filterColumn, $user->id)
            ->first();

        if (!$template) {
            return response()->json(['error' => 'No configuration found for this view type.'], 404);
        }

        return response()->json([
            'template' => $template->configuration
        ]);
    }









    public function usertemplates(Request $request)
    {
        $user = Auth::user();
        $visibilityLevels = VisibilityLevel::all();
        $templates = TemplateConfiguration::with('admin', 'user', 'visibilityLevels')->where('user_id', $user->id)->get();
        if ($request->wantsJson()) {
            return response()->json([
                'templates' => $templates,
            ]);
        }
        return view('templates.index', compact('templates', 'visibilityLevels'));
    }
}
