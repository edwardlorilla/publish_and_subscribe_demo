<?php
namespace App\Http\Controllers;
use App\Publication;
use Illuminate\Http\Request;
class PublicationController extends Controller
{
    public function index()
    {
        return response()->json(Publication::orderBy(request('column') ? request('column') : 'updated_at', request('direction') ? request('direction') : 'desc')
            ->with('user', 'tags')
            ->search(request('search'))
            ->paginate());
    }
    public function search()
    {
        return response()->json(Publication::search(request('search'))->paginate());
    }
    public function store(Request $request)
    {
        $input = $this->validate($request, [
            'user_id' => 'required',
            'title' => 'required|string|max:255',
            'body' => 'string|min:6',
        ]);
        $publication = new Publication;
        $publication->user_id = $input['user_id'];
        $publication->title = $input['title'];
        $publication->body = $input['body'];
        $publication->save();
        if ($request->tags) {
            $tags = $request->tags;
            $user_id = $input['user_id'];
            $publication->tags()->attach($tags);
            $subscription_id = \App\Subscription::whereUserId($user_id)->pluck('subscriber_id');
            $users = \App\User::whereIn('id', $subscription_id)->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('tag_id', $tags);
            })->get();
            foreach ($users as $user){
                $user->notify(new \App\Notifications\PublicationWasUpdated($publication));
            }
        }
        return response()->json($publication, 201);
    }
    public function show(Publication $publication)
    {
        return response()->json(Publication::whereId($publication->id)->with('user', 'tags')->first());
    }
    public function update(Request $request, Publication $publication)
    {
        $input = $this->validate($request, [
            'user_id' => 'required',
            'title' => 'required|string|max:255',
            'body' => 'string|min:6',
        ]);
        $publication->user_id = $input['user_id'];
        $publication->title = $input['title'];
        $publication->body = $input['body'];
        $publication->save();

        $sync =$publication->tags()->sync($request->tags);

        $attach = $sync['attached'];
        $detach = $sync['detached'];
        $types = [];
        if ($sync['attached']) {
            $subscription_id = \App\Subscription::whereUserId($input['user_id'])->pluck('subscriber_id');
            $users = \App\User::whereIn('id', $subscription_id)->whereHas('tags', function ($query) use ($attach) {
                $query->whereIn('tag_id', $attach);
            })->get();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\PublicationWasUpdated($publication));
            }
        }
        if ($sync['detached']) {
            $subscription_id = \App\Subscription::whereUserId($input['user_id'])->pluck('subscriber_id');
            $users = \App\User::whereIn('id', $subscription_id)->whereHas('tags', function ($query) use ($detach) {
                $query->whereIn('tag_id', $detach);
            })->get();
            foreach ($users as $user) {
                foreach ($user->notifications as $notification) {
                    $notification->where('data', 'like', '%"publication_id":' . $publication->id . '%')->delete();
                }
            }
        }
        return response()->json($publication, 201);
    }
    public function destroy(Publication $publication)
    {
        return response()->json($publication->delete());
    }
}