@extends('layouts.app')

@section('content')
<div class="content-holder">
    <div class="container-fluid content pt-3 pb-3">
        <div class="row justify-content-center">
        <div class="col-md-3"></div>
        <div class="col-md-3 following-sugestions">
            <h3 class="title">Following sugestions</h3>
            <form id="search-unfollowed-form" autocomplete="off">
                @csrf
                <input id="search-unfollowed" type="text" name="search_input" class="form-control" placeholder="Search a person you want follow">
            </form>
            <div class="unfollowed-persons-holder">
                @foreach ($suggestions as $person)
                @if ($person->id == auth()->user()->id)
                    @continue
                @endif
                <div class="row person">
                    <div class="col-md-2">
                        {{-- Post image --}}
                        <img class="rounded-circle" src="{{ $person->image ? asset('storage/' . $person->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="User photo">
                    </div>
                    <div class="col-md-6 p-0 pt-2">
                        {{-- Poster name --}}
                        <a href="{{ asset('users/profile/' . $person->id) }}"> {{ $person->name }}</a><br>
                    </div>
                    <div class="col-md-4">
                        <form class="follow-form">
                            @csrf
                            <input type="number" name="followed_id" value="{{$person->id}}" hidden>
                            <button class="btn btn-outline-light" name="submit" type="submit">Follow</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <div id="create-post-msg" class="alert alert-dismissible fade show" hidden>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- New post --}}
            <div class="card post text-white bg-primary mb-3">
                {{-- Post form --}}
                <div class="card-body pb-0 post">
                    <form id="create-post" class="create-post" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <textarea class="post-content" name="content" placeholder="What's on your mind?"></textarea>
                        <label class="lead">Choose an image: </label>
                        <input class="post-image" id="post-image" type="file" name="image">
                        <img id="post-image-preview" src="" alt="Post image" width="100%" hidden>
                        <input type="submit" value="Post" class="form-control btn btn-outline-light mb-3 mt-3">
                    </form>
                </div>
            </div>
            {{-- End new post --}}
            <div class="scrolling-pagination">
                @forelse ($posts as $post)
                {{-- Start post --}}
                <div class="card post post{{$post->id}} text-white bg-primary mb-3 mt-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-1">
                                {{-- Post image --}}
                                <img class="rounded-circle" src="{{ $post->user->image ? asset('storage/' . $post->user->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="User photo">
                            </div>
                            <div class="col-md-10">
                                {{-- Poster name --}}
                                <a class="poster" href="{{ asset('users/profile/' . $post->user->id) }}"> {{ $post->user->name }}</a><br>
                                <small>{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                            {{-- If this is my post i can edit  --}}
                            @if (auth()->user()->posts->contains($post))
                            <div class="col-md-1">
                                <div class="dropdown">
                                    <button class="btn btn-transparent text-white edit-post" type="button" id="edit-post{{$post->id . $rand}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ...
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="edit-post{{$post->id . $rand}}">
                                        <button class="dropdown-item edit-post-operation" data-post-id="{{$post->id}}">Edit</button>
                                        <form  class="delete-post-form" method="post" action="">
                                            @csrf
                                            @method('DELETE')
                                            <input type="number" name="post_id" value="{{$post->id}}" hidden>
                                            <button class="dropdown-item" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{-- end if --}}
                        </div>
                    </div>
                    <div class="card-body pb-0 post">
                        {{-- Edit post form --}}
                        <form class="edit-post-form edit-post-form{{$post->id}}" method="post" action="" enctype="multipart/form-data" autocomplete="off" hidden>
                            @csrf
                            @method('PUT')
                            <input type="number" value="{{$post->id}}" name="post_id" hidden>
                            <input type="text" value="posts" name="images_folder" hidden>
                            <textarea class="post-content" name="content" placeholder="Edit post content">{{$post->content}}</textarea>
                            <label class="lead">Edit post image: </label>
                            <input class="post-image" type="file" name="image">
                            <br><br>
                            <input type="submit" value="Edit" class="btn btn-success edit-btn">
                            <button class="cancel-edit-post btn btn-danger edit-btn" data-post-id="{{$post->id}}">Cancel</button>
                        </form>
                        {{-- Post content --}}
                        <div class="post-content-div{{$post->id}}">
                            <div class="created-post-content">
                                {{$post->content}}
                            </div>
                            @if ($post->image)
                            {{-- Post image --}}
                            <img class="rounded" src="{{asset('storage/' . $post->image->file_name)}}" alt="Post image" width="100%">
                            @else
                            <img class="rounded" src="" alt="Post image" width="100%" hidden>
                            @endif
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-4">
                                <a href="#" data-toggle="modal" data-target="#post-likers{{$post->id}}">
                                    <small class="text-warning"><span class="likes-count{{$post->id}}">{{ $post->likes->count() }}</span> Likes</small>
                                </a>
                                {{-- People who liked this post popup --}}
                                <div class="modal fade" id="post-likers{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="post-liker-modal{{$post->id}}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-secondary" id="post-liker-modal{{$post->id}}">People who liked this post</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body">
                                            @foreach ($post->likes as $like)
                                                <div class="row text-secondary mt-3 liker">
                                                    <div class="col-md-1">
                                                        <img class="rounded-circle" src="{{ $like->user->image ? asset('storage/' . $like->user->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="User photo">
                                                    </div>
                                                    <div class="col-md-8 ml-2">
                                                        <a href="{{asset('users/profile/' . $like->user->id)}}"> {{ $like->user->name }}</a><br>
                                                        <small>{{ $like->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <form class="follow-form">
                                                            @csrf
                                                            <input type="number" name="followed_id" value="{{$like->user->id}}" hidden>
                                                            @if (auth()->user()->following()->get()->contains($like->user))
                                                            <button class="btn btn-light" name="submit" type="submit">Unfollow</button>
                                                            @else
                                                            <button class="btn btn-light" name="submit" type="submit">Follow</button>
                                                            @endif
                                                        </form>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 ml-auto">
                                <small class="text-warning">{{ $post->comments->count() }} Comments</small>
                            </div>
                        </div>
                        <div class="row">
                            {{-- Like a post --}}
                            <div class="col-sm-12 text-center mt-2">
                                @if ($post->likes->every(function ($value, $key) {
                                    return $value->user->id != auth()->user()->id;
                                }))
                                <form class="create-like">
                                    @csrf
                                    <input type="number" name="element-number" value="{{$post->id}}" hidden>
                                    <input type="number" name="likeable_id" value="{{$post->id}}" hidden>
                                    <input type="text" name="likeable_type" value="App\Post" hidden>
                                    <button type="submit" name="submit" class="btn btn-outline-light post-like">Like</button>
                                </form>
                                @else
                                <form class="create-like">
                                    @csrf
                                    <input type="number" name="element-number" value="{{$post->id}}" hidden>
                                    <input type="number" name="likeable_id" value="{{$post->id}}" hidden>
                                    <input type="text" name="likeable_type" value="App\Post" hidden>
                                    <button type="submit" name="submit" class="btn btn-outline-light post-like">Unlike</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{-- Comment --}}
                    @foreach ($post->comments as $comment)
                    <div class="card-body pt-0 pb-0 comment{{$comment->id . $rand2}}">
                        <div class="row">
                            <div class="col-md-1">
                                {{-- Commenter image --}}
                                <img class="rounded-circle" src="{{ $comment->user->image ? asset('storage/' . $comment->user->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="Commenter photo">
                            </div>
                            <div class="col-md-11">
                                @if (auth()->user()->posts->contains($post) || auth()->user()->comments->contains($comment))
                                <div class="dropdown">
                                    <button class="btn btn-transparent text-white edit-post edit-comment" type="button" id="edit-post" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ...
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="edit-post">
                                        @if ($comment->user->id == auth()->user()->id)
                                        <button class="dropdown-item edit-comment-operation" data-comment-id="{{$comment->id . $rand2}}">Edit</button>
                                        @endif
                                        <form  class="delete-comment-form" method="post" action="">
                                            @csrf
                                            @method('DELETE')
                                            <input type="number" name="comment_id" value="{{$comment->id}}" hidden>
                                            <input type="number" name="rand" value="{{$rand2}}" hidden>
                                            <button class="dropdown-item" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                                <div>
                                    {{-- commenter name --}}
                                    <a class="text-warning" href="{{ asset('users/profile/' . $comment->user->id) }}"> {{ $comment->user->name }}</a><br>
                                    <small>{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                {{-- Edit comment form --}}
                                <form class="edit-comment-form edit-comment-form{{$comment->id . $rand2}}" method="post" action="" enctype="multipart/form-data" autocomplete="off" hidden>
                                    @csrf
                                    @method('PUT')
                                    <input type="number" value="{{$post->id}}" name="post_id" hidden>
                                    <input type="number" value="{{$rand2}}" name="rand" hidden>
                                    <input type="number" value="{{$comment->id}}" name="comment_id" hidden>
                                    <input type="text" value="comments" name="images_folder" hidden>
                                    <textarea class="post-content" name="content" placeholder="Edit comment content">{{$comment->content}}</textarea>
                                    <label class="lead">Edit comment image: </label>
                                    <input class="comment-image" type="file" name="image">
                                    <br><br>
                                    <input type="submit" value="Edit" class="btn btn-success edit-btn">
                                    <button class="cancel-edit-comment btn btn-danger edit-btn" data-comment-id="{{$comment->id . $rand2}}">Cancel</button>
                                </form>
                                {{-- Comment content --}}
                                <div class="comment-content-div{{$comment->id . $rand2}}">
                                    <div class="created-comment-content">
                                        {{ $comment->content }}
                                    </div>
                                    @if ($comment->image)
                                    {{-- Comment image --}}
                                    <img class="rounded" src="{{asset('storage/' . $comment->image->file_name)}}" alt="Comment image" width="100%">
                                    @else
                                    <img class="rounded" src="" alt="Comment image" width="100%" hidden>
                                    @endif
                                </div>
                                <div class="row mt-4">
                                    <div class="col-sm-6">
                                        {{-- Like a comment --}}
                                        @if ($comment->likes->every(function ($value, $key) {
                                            return $value->user->id != auth()->user()->id;
                                        }))
                                        <form class="create-like">
                                            @csrf
                                            <input type="number" name="element-number" value="{{$comment->id . $rand2}}" hidden>
                                            <input type="number" name="likeable_id" value="{{$comment->id}}" hidden>
                                            <input type="text" name="likeable_type" value="App\Comment" hidden>
                                            <button type="submit" name="submit" class="btn btn-outline-light comment-like">Like</button>
                                        </form>
                                        @else
                                        <form class="create-like">
                                            @csrf
                                            <input type="number" name="element-number" value="{{$comment->id . $rand2}}" hidden>
                                            <input type="number" name="likeable_id" value="{{$comment->id}}" hidden>
                                            <input type="text" name="likeable_type" value="App\Comment" hidden>
                                            <button type="submit" name="submit" class="btn btn-outline-light comment-like">Unlike</button>
                                        </form>
                                        @endif
                                    </div>
                                    <div class="col-sm-3 ml-auto">
                                        {{-- Comment likes count --}}
                                        <a href="#" data-toggle="modal" data-target="#comment-likers{{$comment->id . $rand2}}">
                                            <small class="text-warning"><span class="likes-count{{$comment->id . $rand2}}">{{ $comment->likes->count() }}</span> Likes</small>
                                        </a> -
                                        {{-- Replies count --}}
                                        <small class="text-warning">{{ $comment->replies->count() }} Replies</small>
                                        {{-- People who liked this comment --}}
                                        <div class="modal fade" id="comment-likers{{$comment->id . $rand2}}" tabindex="-1" role="dialog" aria-labelledby="comment-liker-modal{{$comment->id . $rand2}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-secondary" id="comment-liker-modal{{$comment->id . $rand2}}">People who liked this comment</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                    <div class="modal-body">
                                                    @foreach ($comment->likes as $like)
                                                        <div class="row text-secondary mt-3">
                                                            <div class="col-md-1">
                                                                <img class="rounded-circle" src="{{ $like->user->image ? asset('storage/' . $like->user->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="User photo">
                                                            </div>
                                                            <div class="col-md-8 ml-2">
                                                                <a href="{{ asset('users/profile/' . $like->user->id) }}"> {{ $like->user->name }}</a><br>
                                                                <small>{{ $like->created_at->diffForHumans() }}</small>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <form class="follow-form">
                                                                    @csrf
                                                                    <input type="number" name="followed_id" value="{{$like->user->id}}" hidden>
                                                                    @if (auth()->user()->following()->get()->contains($like->user))
                                                                    <button class="btn btn-light" name="submit" type="submit">Unfollow</button>
                                                                    @else
                                                                    <button class="btn btn-light" name="submit" type="submit">Follow</button>
                                                                    @endif
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($comment->replies as $reply)
                                {{-- Reply --}}
                                <div class="card-body p-2 mt-4 reply{{$reply->id . $rand3}} border rounded">
                                    <div class="row">
                                        <div class="col-md-1">
                                            {{-- Replier image --}}
                                            <img class="rounded-circle" src="{{ $reply->user->image ? asset('storage/' . $reply->user->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="Replier photo">
                                        </div>
                                        <div class="col-md-11">
                                            @if (auth()->user()->posts->contains($post) || auth()->user()->comments->contains($comment) || auth()->user()->replies->contains($reply))
                                            <div class="dropdown">
                                                <button class="btn btn-transparent text-white edit-post edit-comment" type="button" id="edit-post" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    ...
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="edit-post">
                                                    @if ($reply->user->id == auth()->user()->id)
                                                    <button class="dropdown-item edit-reply-operation" data-reply-id="{{$reply->id . $rand3}}">Edit</button>
                                                    @endif
                                                    <form  class="delete-reply-form" method="post" action="">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="number" name="reply_id" value="{{$reply->id}}" hidden>
                                                        <input type="number" name="rand" value="{{$rand3}}" hidden>
                                                        <button class="dropdown-item" type="submit">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                            @endif
                                            <div>
                                                {{-- Replier name --}}
                                                <a class="text-warning" href="{{ asset('users/profile/' . $reply->user->id) }}"> {{ $reply->user->name }}</a><br>
                                                <small>{{ $reply->created_at->diffForHumans() }}</small>
                                            </div>
                                            {{-- Edit reply form --}}
                                            <form class="edit-reply-form edit-reply-form{{$reply->id . $rand3}}" method="post" action="" enctype="multipart/form-data" autocomplete="off" hidden>
                                                @csrf
                                                @method('PUT')
                                                <input type="number" value="{{$comment->id}}" name="comment_id" hidden>
                                                <input type="number" value="{{$rand3}}" name="rand" hidden>
                                                <input type="number" value="{{$reply->id}}" name="reply_id" hidden>
                                                <input type="text" value="replies" name="images_folder" hidden>
                                                <textarea class="post-content" name="content" placeholder="Edit reply content">{{$reply->content}}</textarea>
                                                <label class="lead">Edit reply image: </label>
                                                <input class="reply-image" type="file" name="image">
                                                <br><br>
                                                <input type="submit" value="Edit" class="btn btn-success edit-btn">
                                                <button class="cancel-edit-reply btn btn-danger edit-btn" data-reply-id="{{$reply->id . $rand3}}">Cancel</button>
                                            </form>
                                            {{-- Reply content and image --}}
                                            <div class="reply-content-div{{$reply->id . $rand3}}">
                                                <div class="created-reply-content">
                                                    {{ $reply->content }}
                                                </div>
                                                @if ($reply->image)
                                                {{-- reply image --}}
                                                <img class="rounded" src="{{asset('storage/' . $reply->image->file_name)}}" alt="reply image" width="100%">
                                                @else
                                                <img class="rounded" src="" alt="reply image" width="100%" hidden>
                                                @endif
                                            </div>
                                            <div class="row mt-4">
                                                {{-- Like a reply --}}
                                                <div class="col-sm-6">
                                                    @if ($reply->likes->every(function ($value, $key) {
                                                        return $value->user->id != auth()->user()->id;
                                                    }))
                                                    <form class="create-like">
                                                        @csrf
                                                        <input type="number" name="element-number" value="{{$reply->id}}" hidden>
                                                        <input type="number" name="likeable_id" value="{{$reply->id}}" hidden>
                                                        <input type="text" name="likeable_type" value="App\Reply" hidden>
                                                        <button type="submit" name="submit" class="btn btn-outline-light reply-like">Like</button>
                                                    </form>
                                                    @else
                                                    <form class="create-like">
                                                        @csrf
                                                        <input type="number" name="element-number" value="{{$reply->id}}" hidden>
                                                        <input type="number" name="likeable_id" value="{{$reply->id}}" hidden>
                                                        <input type="text" name="likeable_type" value="App\Reply" hidden>
                                                        <button type="submit" name="submit" class="btn btn-outline-light reply-like">Unlike</button>
                                                    </form>
                                                    @endif
                                                </div>
                                                <div class="col-sm-2 ml-auto">
                                                    <a href="#" data-toggle="modal" data-target="#reply-likers{{$reply->id}}">
                                                        <small class="text-warning"><span class="likes-count{{$reply->id}}">{{ $reply->likes->count() }}</span> Likes</small>
                                                    </a>
                                                </div>
                                                {{-- People who liked this reply --}}
                                                <div class="modal fade" id="reply-likers{{$reply->id}}" tabindex="-1" role="dialog" aria-labelledby="reply-liker-modal{{$reply->id}}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title text-secondary" id="reply-liker-modal{{$reply->id}}">People who liked this reply</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            @foreach ($reply->likes as $like)
                                                                <div class="row text-secondary mt-3">
                                                                    <div class="col-md-1">
                                                                        <img class="rounded-circle" src="{{ $like->user->image ? asset('storage/' . $like->user->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="User photo">
                                                                    </div>
                                                                    <div class="col-md-8 ml-2">
                                                                        <a href="{{ asset('users/profile/' . $like->user->id) }}"> {{ $like->user->name }}</a><br>
                                                                        <small>{{ $like->created_at->diffForHumans() }}</small>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <form class="follow-form">
                                                                            @csrf
                                                                            <input type="number" name="followed_id" value="{{$like->user->id}}" hidden>
                                                                            @if (auth()->user()->following()->get()->contains($like->user))
                                                                            <button class="btn btn-light" name="submit" type="submit">Unfollow</button>
                                                                            @else
                                                                            <button class="btn btn-light" name="submit" type="submit">Follow</button>
                                                                            @endif
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="card-body p-2 mt-4 reply border rounded preview-reply{{$comment->id}}" hidden>
                                    <div class="row">
                                        <div class="col-md-1">
                                            {{-- Replier image --}}
                                            <img class="rounded-circle" src="{{ auth()->user()->image ? asset('storage/' . auth()->user()->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="Replier photo">
                                        </div>
                                        <div class="col-md-11">
                                            <div>
                                                {{-- Replier name --}}
                                                <a class="text-warning" href="{{ asset('users/profile/' . auth()->user()->id) }}"> {{ auth()->user()->name }}</a><br>
                                                <small>Just now</small>
                                            </div>
                                            {{-- Reply content and image --}}
                                            <span class="preview-reply-content{{$comment->id}}"></span><br><br>
                                            <img class="rounded preview-reply-img{{$comment->id}}" src="" alt="Reply image" width="100%" hidden>
                                            <div class="row mt-4">
                                                {{-- Like a reply --}}
                                                <div class="col-sm-6">
                                                    <form class="create-like">
                                                        @csrf
                                                        <input type="number" name="element-number" value="{{$rand . $comment->id}}" hidden>
                                                        <input type="number" class="preview-reply-like-id{{$comment->id}}" name="likeable_id" hidden>
                                                        <input type="text" name="likeable_type" value="App\Reply" hidden>
                                                        <button type="submit" name="submit" class="btn btn-outline-light reply-like">Like</button>
                                                    </form>
                                                </div>
                                                <div class="col-sm-2 ml-auto">
                                                    <a href="#" data-toggle="modal" data-target="#reply-likers{{$rand . $comment->id}}">
                                                        <small class="text-warning"><span class="likes-count{{$rand . $comment->id}}">0</span> Likes</small>
                                                    </a>
                                                </div>
                                                {{-- People who liked this reply --}}
                                                <div class="modal fade" id="reply-likers{{$rand . $comment->id}}" tabindex="-1" role="dialog" aria-labelledby="reply-liker-modal{{$rand . $comment->id}}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title text-secondary" id="reply-liker-modal{{$rand . $comment->id}}">People who liked this reply</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-dismissible fade show create-reply-msg{{$comment->id}}" hidden>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="reply pt-2">
                                    {{-- Add reply --}}
                                    <form class="create-reply" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        <div class="form-group input-holder">
                                            <input type="text" class="form-control select-image-input" name="content" placeholder="Type a reply">
                                            <input class="reply-image" type="file" name="image" hidden>
                                            <i class="fa fa-camera select-image"></i>
                                        </div>
                                        <input type="number" name="comment_id" value="{{$comment->id}}" hidden>
                                        <button type="submit" class="btn btn-outline-light">Reply</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    @endforeach
                    <div class="card-body pt-0 pb-0 comment preview-comment{{$post->id}}" hidden>
                        <div class="row">
                            <div class="col-md-1">
                                {{-- Commenter image --}}
                                <img class="rounded-circle" src="{{ auth()->user()->image ? asset('storage/' . auth()->user()->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="Commenter photo">
                            </div>
                            <div class="col-md-11">
                                <div>
                                    {{-- commenter name --}}
                                    <a class="text-warning" href="{{ asset('users/profile/' . auth()->user()->id) }}"> {{ auth()->user()->name }}</a><br>
                                    <small>Just now</small>
                                </div>
                                {{-- Comment content and image --}}
                                <span class="preview-comment-content{{$post->id}}"></span><br><br>
                                <img class="rounded preview-comment-img{{$post->id}}" src="" alt="Comment image" width="100%" hidden>
                                <div class="row mt-4">
                                    <div class="col-sm-6">
                                        {{-- Like a comment --}}
                                        <form class="create-like">
                                            @csrf
                                            <input type="number" name="element-number" value="{{$rand . $post->id}}" hidden>
                                            <input type="number" class="preview-comment-like-id{{$post->id}}" name="likeable_id" hidden>
                                            <input type="text" name="likeable_type" value="App\Comment" hidden>
                                            <button type="submit" name="submit" class="btn btn-outline-light comment-like">Like</button>
                                        </form>
                                    </div>
                                    <div class="col-sm-3 ml-auto">
                                        {{-- Comment likes count --}}
                                        <a href="#" data-toggle="modal" data-target="#comment-likers{{$rand . $post->id}}">
                                            <small class="text-warning"><span class="likes-count{{$rand . $post->id}}">0</span> Likes</small>
                                        </a> -
                                        {{-- Replies count --}}
                                        <small class="text-warning">0 Replies</small>
                                        {{-- People who liked this comment --}}
                                        <div class="modal fade" id="comment-likers{{$rand . $post->id}}" tabindex="-1" role="dialog" aria-labelledby="comment-liker-modal{{$rand . $post->id}}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title text-secondary" id="comment-liker-modal{{$rand . $post->id}}">People who liked this comment</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="alert alert-dismissible fade show create-comment-msg{{$post->id}}" hidden>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body pt-0">
                        {{-- Add comment --}}
                        <form class="create-comment" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="form-group input-holder">
                                <input type="text" class="form-control select-image-input" name="content" placeholder="Type a comment">
                                <input class="comment-image" type="file" name="image" hidden>
                                <i class="fa fa-camera select-image"></i>
                            </div>
                            <input type="number" name="post_id" value="{{$post->id}}" hidden>
                            <button type="submit" class="btn btn-outline-light">Comment</button>
                        </form>
                    </div>
                </div>
                {{-- End post --}}
                @empty
                <div class="alert alert-danger mt-5">No posts for now!</div>
                @endforelse
                {{$posts->links()}}
            </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3 following-people">
            <h3 class="title">People you follow</h3>
            <form id="search-followed-form" autocomplete="off">
                @csrf
                <input id="search-followed" type="text" name="search_input" class="form-control" placeholder="Search a person you follow">
            </form>
            <div class="followed-persons-holder">
                @foreach ($following as $person)
                <div class="row person">
                    <div class="col-md-2">
                        {{-- Post image --}}
                        <img class="rounded-circle" src="{{ $person->image ? asset('storage/' . $person->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="User photo">
                    </div>
                    <div class="col-md-6 p-0 pt-2">
                        {{-- Poster name --}}
                        <a href="{{asset('users/profile/' . $person->id)}}"> {{ $person->name }}</a><br>
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-outline-light" href="{{ asset('users/messages/' . $person->id) }}">Message</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>
@endsection
