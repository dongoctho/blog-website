<?php

class Model_CommentReaction extends \Orm\Model
{
    protected static $_properties = array(
        'id' => array(
            'label' => 'Id',
            'data_type' => 'int',
        ),
        'user_id' => array(
            'label' => 'User id',
            'data_type' => 'int',
        ),
        'comment_id' => array(
            'label' => 'Comment id',
            'data_type' => 'int',
        ),
        'reaction_type' => array(
            'label' => 'Reaction type',
            'data_type' => 'varchar',
        ),
        'created_at' => array(
            'label' => 'Created at',
            'data_type' => 'datetime',
        ),
        'updated_at' => array(
            'label' => 'Updated at',
            'data_type' => 'datetime',
        ),
    );

    protected static $_table_name = 'comment_reactions';

    protected static $_primary_key = array('id');

    protected static $_belongs_to = array(
        'comment' => array(
            'model_to' => 'Model_Comment',
            'key_from' => 'comment_id',
            'key_to' => 'id',
        ),
        'user' => array(
            'model_to' => 'Model_User',
            'key_from' => 'user_id',
            'key_to' => 'id',
        ),
    );
}
