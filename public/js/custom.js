// Live preview for post image
let input = document.getElementById('post-image') || null;
let preview = document.getElementById('post-image-preview') || null;
let postFeedback = document.getElementById('create-post-msg') || null;
let host = "http://" + window.location.host + "/";

function readURL(input, previewImg) {
    if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
        previewImg.setAttribute('src', e.target.result);
        previewImg.hidden = false;
    }

    reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

if (input != null) {
    input.addEventListener('input', function () {
        readURL(this, preview);
    });
}

// New post ajax request
$('#create-post').on('submit', function (e) {
    e.preventDefault();
    let image = this['image'];
    let content = this['content'];

    if (!content.value && !image.value) {
        alert("You can't post nothing!");
    } else {
        $.ajax({
            url: "/posts",
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                let msg = document.createTextNode(data.message);
                postFeedback.hidden = false;
                postFeedback.prepend(msg);
                if (data.status == 1) {
                    postFeedback.classList.remove("alert-danger");
                    postFeedback.classList.add("alert-success");
                } else {
                    postFeedback.classList.add("alert-danger");
                    postFeedback.classList.remove("alert-success");
                }
            }
        });
    }

    content.value = "";
    image.value = null;
    preview.hidden = true;
});

// New like ajax request
$(document).on('submit', '.create-like', function (e) {
    e.preventDefault();
    let submitBtn = this['submit'];
    let likesCount = document.querySelector('.likes-count' + this['element-number'].value);
    submitBtn.disabled = true;

    $.ajax({
        url: "/like",
        type: "POST",
        data: new FormData(this),
        dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            if (data.status == 1) {
                submitBtn.disabled = false;
                if (submitBtn.innerText == 'Like') {
                    submitBtn.innerText = 'Unlike';
                    likesCount.innerText = parseInt(likesCount.innerText) + 1;
                } else {
                    likesCount.innerText = parseInt(likesCount.innerText) - 1;
                    submitBtn.innerText = 'Like';
                }
            }
        }
    });
});

// Trigger file input in comment when clicking it's icon
$(document).on('click', ".select-image", function () {
    this.previousElementSibling.click();
});

// New comment ajax request
$(document).on('submit', '.create-comment', function (e) {
    e.preventDefault();
    let content = this['content'];
    let img = this['image'];
    let previewCommentHolder = document.querySelector('.preview-comment' + this['post_id'].value);
    let previewImage = document.querySelector('.preview-comment-img' + this['post_id'].value);
    let previewLikeId = document.querySelector('.preview-comment-like-id' + this['post_id'].value);
    let previewContent = document.querySelector('.preview-comment-content' + this['post_id'].value);
    let commentFeedback = document.querySelector('.create-comment-msg' + this['post_id'].value) || null;

    if (!img.value && !content.value){
        alert("You can't comment nothing!");
    } else {
        $.ajax({
            url: "/comments",
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == 1) {
                    readURL(img, previewImage);
                    previewContent.innerText = content.value;
                    previewLikeId.value = data.comment_id;
                    previewCommentHolder.hidden = false;
                } else {
                    let msg = document.createTextNode(data.message);
                    commentFeedback.hidden = false;
                    commentFeedback.prepend(msg);
                    commentFeedback.classList.add("alert-danger");
                }
                content.value = '';
            }
        });
    }
});

// New message ajax request
$(document).on('submit', '.create-message', function (e) {
    e.preventDefault();
    let content = this['content'];
    let img = this['image'];

    if (!img.value && !content.value){
        alert("You can't send nothing!");
    } else {
        $.ajax({
            url: "/messages",
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                // console.log(data);
                let messagesHolder = document.querySelector('.messages-holder');
                messagesHolder.insertAdjacentHTML('afterbegin', `
                <div class="message from-me float-right">
                    <div>
                        ${content.value}
                    </div>
                    <img src="${ data.image ? '../../storage/' + data.image : '' }" width="100%" alt="Message photo"${ data.image ? '' : ' hidden'}>
                    <span class="text-info date">Just now</span>
                </div>
                <div class='clear-fix'></div>
                `);
                content.value = '';
                img.value = '';
            }
        });
    }
});

// New reply ajax request
$(document).on('submit', '.create-reply', function (e) {
    e.preventDefault();
    let content = this['content'];
    let img = this['image'];
    let previewReplyHolder = document.querySelector('.preview-reply' + this['comment_id'].value);
    let previewImage = document.querySelector('.preview-reply-img' + this['comment_id'].value);
    let previewLikeId = document.querySelector('.preview-reply-like-id' + this['comment_id'].value);
    let previewContent = document.querySelector('.preview-reply-content' + this['comment_id'].value);
    let replyFeedback = document.querySelector('.create-reply-msg' + this['comment_id'].value);
    console.log(replyFeedback);

    if (!img.value && !content.value){
        alert("You can't reply nothing!");
    } else {
        $.ajax({
            url: "/replies",
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == 1) {
                    readURL(img, previewImage);
                    previewContent.innerText = content.value;
                    previewLikeId.value = data.reply_id;
                    previewReplyHolder.hidden = false;
                } else {
                    let msg = document.createTextNode(data.message);
                    replyFeedback.hidden = false;
                    replyFeedback.prepend(msg);
                    replyFeedback.classList.add("alert-danger");
                }
                content.value = '';
                console.log(data);
            }
        });
    }
});

// Edit post onclick on edit operation
$(document).on('click', '.edit-post-operation', function () {
    let postId = this.dataset.postId;
    document.querySelector('.edit-post-form' + postId).hidden = false;
    document.querySelector('.post-content-div' + postId).hidden = true;
});

// Cancel editing post
$(document).on('click', '.cancel-edit-post', function (e) {
    e.preventDefault();
    let postId = this.dataset.postId;
    document.querySelector('.edit-post-form' + postId).hidden = true;
    document.querySelector('.post-content-div' + postId).hidden = false;
});

// Edit comment onclick on edit operation
$(document).on('click', '.edit-comment-operation', function () {
    let commentId = this.dataset.commentId;
    console.log(commentId);
    document.querySelector('.edit-comment-form' + commentId).hidden = false;
    document.querySelector('.comment-content-div' + commentId).hidden = true;
});

// Cancel editing comment
$(document).on('click', '.cancel-edit-comment', function (e) {
    e.preventDefault();
    let commentId = this.dataset.commentId;
    document.querySelector('.edit-comment-form' + commentId).hidden = true;
    document.querySelector('.comment-content-div' + commentId).hidden = false;
});

// Edit reply onclick on edit operation
$(document).on('click', '.edit-reply-operation', function () {
    let replyId = this.dataset.replyId;
    console.log(replyId);
    document.querySelector('.edit-reply-form' + replyId).hidden = false;
    document.querySelector('.reply-content-div' + replyId).hidden = true;
});

// Cancel editing reply
$(document).on('click', '.cancel-edit-reply', function (e) {
    e.preventDefault();
    let replyId = this.dataset.replyId;
    document.querySelector('.edit-reply-form' + replyId).hidden = true;
    document.querySelector('.reply-content-div' + replyId).hidden = false;
});


// Edit post ajax request
$(document).on('submit', '.edit-post-form', function (e) {
    e.preventDefault();
    let image = this['image'];
    let content = this['content'];
    let post_id = this['post_id'].value;

    if (!content.value && !image.value) {
        alert("You can't post nothing!");
    } else {
        $.ajax({
            url: "/posts/" + post_id,
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                let postImage = document.querySelector('.post-content-div' + post_id + ' img');
                if (data.image) {
                    postImage.setAttribute('src', host + 'storage/' + data.image);
                    postImage.hidden = false;
                } else {
                    postImage.hidden = true;
                }
                document.querySelector('.post-content-div' + post_id + ' .created-post-content').innerText = content.value;
                document.querySelector('.edit-post-form' + post_id).hidden = true;
                document.querySelector('.post-content-div' + post_id).hidden = false;
            }
        });
    }
});

// Edit comment ajax request
$(document).on('submit', '.edit-comment-form', function (e) {
    e.preventDefault();
    let image = this['image'];
    let content = this['content'];
    let rand = this['rand'].value;
    let comment_id = this['comment_id'].value;

    if (!content.value && !image.value) {
        alert("You can't comment nothing!");
    } else {
        $.ajax({
            url: "/comments/" + comment_id,
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                console.log(data);
                let commentImage = document.querySelector('.comment-content-div' + comment_id + rand + ' img');
                if (data.image) {
                    commentImage.setAttribute('src', host + 'storage/' + data.image);
                    commentImage.hidden = false;
                } else {
                    commentImage.hidden = true;
                }
                document.querySelector('.comment-content-div' + comment_id + rand + ' .created-comment-content').innerText = content.value;
                document.querySelector('.edit-comment-form' + comment_id + rand).hidden = true;
                document.querySelector('.comment-content-div' + comment_id + rand).hidden = false;
            }
        });
    }
});

// Edit reply ajax request
$(document).on('submit', '.edit-reply-form', function (e) {
    e.preventDefault();
    let image = this['image'];
    let content = this['content'];
    let rand = this['rand'].value;
    let reply_id = this['reply_id'].value;

    if (!content.value && !image.value) {
        alert("You can't reply nothing!");
    } else {
        $.ajax({
            url: "/replies/" + reply_id,
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                console.log(data);
                let replyImage = document.querySelector('.reply-content-div' + reply_id + rand + ' img');
                if (data.image) {
                    replyImage.setAttribute('src', host + 'storage/' + data.image);
                    replyImage.hidden = false;
                } else {
                    replyImage.hidden = true;
                }
                document.querySelector('.reply-content-div' + reply_id + rand + ' .created-reply-content').innerText = content.value;
                document.querySelector('.edit-reply-form' + reply_id + rand).hidden = true;
                document.querySelector('.reply-content-div' + reply_id + rand).hidden = false;
            }
        });
    }
});

// Preview image onEditProfile
let editProfileInput = document.querySelector('.edit-profile-input');

if (editProfileInput) {
    editProfileInput.addEventListener('input', function () {
        readURL(this, document.querySelector('.edit-profile-form img'));
    });
}

// Delete post ajax request
$(document).on('submit', '.delete-post-form', function (e) {
    e.preventDefault();
    let post_id = this['post_id'].value;

    if (!post_id) {
        alert("It's not from ethics to hack my website!");
    } else {
        $.ajax({
            url: "/posts/" + post_id,
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status = 1) {
                    document.querySelector('.post' + post_id).hidden = true;
                    console.log(data.message);
                }
            }
        });
    }
});

// Delete comment ajax request
$(document).on('submit', '.delete-comment-form', function (e) {
    e.preventDefault();
    let comment_id = this['comment_id'].value;
    let rand = this['rand'].value;

    if (!comment_id) {
        alert("It's not from ethics to hack my website!");
    } else {
        $.ajax({
            url: "/comments/" + comment_id,
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status = 1) {
                    document.querySelector('.comment' + comment_id + rand).hidden = true;
                    console.log(data.message);
                }
            }
        });
    }
});

// Delete reply ajax request
$(document).on('submit', '.delete-reply-form', function (e) {
    e.preventDefault();
    let reply_id = this['reply_id'].value;
    let rand = this['rand'].value;

    if (!reply_id) {
        alert("It's not from ethics to hack my website!");
    } else {
        $.ajax({
            url: "/replies/" + reply_id,
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status = 1) {
                    document.querySelector('.reply' + reply_id + rand).hidden = true;
                    console.log(data.message);
                }
            }
        });
    }
});

// Follow person ajax request
$(document).on('submit', '.follow-form', function (e) {
    e.preventDefault();
    let submitBtn = this['submit'];
    submitBtn.disabled = true;
    let followed = this['followed_id'].value;

    if (!followed) {
        alert("It's not from ethics to hack my website!");
    } else {
        $.ajax({
            url: "/users/toggleFollow/" + followed,
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                if (data.status == 1) {
                    submitBtn.innerText = "Unfollow";
                } else {
                    submitBtn.innerText = "Follow";
                }
                submitBtn.disabled = false;
            }
        });
    }
});

$(document).on('submit', '#search-followed-form', function (e) {
    e.preventDefault();

    $.ajax({
        url: "/users/searchFollowed",
        type: "POST",
        data: new FormData(this),
        dataType: "JSON",
        cache: false,
        contentType: false,
        processData: false,

        success: function (data) {
            console.log(data.users);
        }
    });
});

// Search followed person ajax request
$(document).on('keyup', '#search-followed', function (e) {
    document.getElementById("search-followed-form").submit = function () {
        // e.preventDefault();

        $.ajax({
            url: "/users/searchFollowed",
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                let personsHolder = document.querySelector('.followed-persons-holder');
                // console.log(data.users);
                let content = '';
                for (user of data.users) {
                    // console.log(user[0]); // name
                    content += `<div class="row person">
                                    <div class="col-md-2">
                                        <img class="rounded-circle" src="storage/${user[2]}" width="40px" height="40px" alt="User photo">
                                    </div>
                                    <div class="col-md-6 p-0 pt-2">
                                        <a href="users/profile/${user[0]}"> ${user[1]}</a><br>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-outline-light" href='users/messages/${user[0]}'>Message</a>
                                    </div>
                                </div>`;
                }

                personsHolder.innerHTML = content;
            }
        });
    };

    document.getElementById("search-followed-form").submit();
});

// Search unfollowed person ajax request
$(document).on('keyup', '#search-unfollowed', function (e) {
    document.getElementById("search-unfollowed-form").submit = function () {
        // e.preventDefault();

        $.ajax({
            url: "/users/searchUnfollowed",
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,

            success: function (data) {
                let personsHolder = document.querySelector('.unfollowed-persons-holder');
                // console.log(data.users);
                let content = '';
                for (user in data.users) {
                    // console.log(data.users[user][3]); // name
                    content += `<div class="row person">
                                    <div class="col-md-2">
                                        <img class="rounded-circle" src="storage/${data.users[user][2]}" width="40px" height="40px" alt="User photo">
                                    </div>
                                    <div class="col-md-6 p-0 pt-2">
                                        <a href="users/profile/${data.users[user][0]}"> ${data.users[user][1]}</a><br>
                                    </div>
                                    <div class="col-md-4">
                                        <form class="follow-form">
                                            <input type="hidden" name="_token" value="${data.users[user][3]}">
                                            <input type="number" name="followed_id" value="${data.users[user][0]}" hidden>
                                            <button class="btn btn-outline-light" name="submit" type="submit">Follow</button>
                                        </form>
                                    </div>
                                </div>`;
                }

                personsHolder.innerHTML = content;
            }
        });
    };

    document.getElementById("search-unfollowed-form").submit();
});

// Real-time message listen
// window.Laravel = {'csrfToken': '{{csrf_token()}}'}
// window.Echo.private(`message.${messageId}`)
//     .listen('MessageSent', (e) => {
//         console.log(e);
//     });

// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;

// var pusher = new Pusher('de0fea79fe5195a82b33', {
//     cluster: 'eu'
// });

// var channel = pusher.subscribe(`message.{{}}`);
// channel.bind('App\\Events\\MessageSent', function(data) {
//     alert(JSON.stringify(data));
// });
