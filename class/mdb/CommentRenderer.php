<?php

namespace mdb;

class CommentRenderer
{
    public function getCommentHTML() {?>
        <div class="comment">
            <div class="comment-pseudo"><?= $this->pseudo ?></div>
            <div class="comment-text"><?= $this->commentaire ?></div>
        </div>
    <?php }
}