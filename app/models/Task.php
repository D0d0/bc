<?php

/**
 * Description of Task
 *
 * @author Jozef Dúc
 */
class Task extends Eloquent {

    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $table = 'subjects';
    protected $fillable = array('subject_id', 'name', 'text', 'start', 'deadline', 'created_at', 'updated_at');

    public function subject() {
        return $this->hasOne('Subject', 'id', 'subject_id');
    }

}
