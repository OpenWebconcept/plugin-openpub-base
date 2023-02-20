<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

class CommentField extends \OWC\OpenPub\Base\Support\CreatesFields
{
    /**
     * The condition for the creator.
     */
    protected function condition(): callable
    {
        return function () {
            return isset($_REQUEST['with']) and (in_array('comments', array_map('trim', explode(',', $_REQUEST['with']))));
        };
    }

    /**
     * Creates an array of comments.
     */
    public function create(\WP_Post $post): array
    {
        $result = [];
        $result['count'] = (int) $post->comment_count;
        $result['status'] = $post->comment_status;
        $result['items'] = [];

        if (!in_array($post->comment_status, ['open'])) {
            return $result;
        }

        $result['items'] = $this->getComments($post->ID);

        return $result;
    }

    /**
     * Get comment items of a post.
     */
    protected function getComments(int $postID): array
    {
        $comments = \get_comments([
            'post_id'      => $postID,
            'status'       => 'approve',
            'hierarchical' => 'threaded'
        ]);

        if (! $comments) {
            return [];
        }

        return array_map(function ($comment) {
            return $this->format($comment, $this->getChild($comment));
        }, array_values($comments));
    }

    /**
     * Get the child(s) of the comment.
     */
    protected function getChild(\WP_Comment $comment): array
    {
        $replies = [];
        $repliesChildren = [];

        foreach ($comment->get_children() as $child) {
            if ($child->get_children()) {
                $repliesChildren = $this->getChild($child);
            }
            $replies[] = $this->format($child, $repliesChildren);
            $repliesChildren = [];
        }

        return $replies;
    }

    /**
     * Format the comment.
     */
    protected function format(\WP_Comment $comment, array $replies = []): array
    {
        return [
            'id'            => (int) $comment->comment_ID,
            'parentid'      => (int) $comment->comment_parent,
            'author'        => $comment->comment_author,
            'content'       => $comment->comment_content,
            'date'          => $comment->comment_date,
            'replies'       => $replies
        ];
    }
}
