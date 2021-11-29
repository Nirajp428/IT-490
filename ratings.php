<html>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<body>
    <a class="social-like" >
        <span class="like"><i 
        class="glyphicon glyphicon-ok"></i></span>
        <span class="like-count" >0</span>
        <button id="add">Like</button>
    </a>
    &nbsp;
    <a class="social-dislike" >
        <span class="dislike-count" >0</span>
        <button id="remove">Dislike</button>
        <span class="like"><i class="glyphicon glyphicon-remove"></i></span>
    </a>
<script>
    let likeCount = 0
    let dislikeCount = 0
    const likeButton = document.querySelector(".social-like")
    const dislikeButton = document.querySelector('.social-dislike')
    const likeCountText = document.querySelector(".like-count")
    const dislikeCountText = document.querySelector(".dislike-count")
    likeButton.addEventListener("click", async () => {
        try {
            
            /* send a request to server, when server successfully stores the like in the database,
            the server sends a response then you update the like count on the frontend
            */
            const response =  await axios.post("http://127.0.0.1:4500/like", {user: "matrix", movieTitle: "avengers"})

            if (response?.data?.success) {
                likeCount++ 
                likeCountText.textContent = likeCount
            }
        } catch (err) {

        }
    })
