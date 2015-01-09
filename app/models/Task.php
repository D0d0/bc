<?php

/**
 * Description of Task
 *
 * @author Jozef Dúc
 */
class Task extends Eloquent {

    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $table = 'tasks';
    protected $fillable = array('subject_id', 'name', 'text', 'test', 'start', 'deadline', 'created_at', 'updated_at');

    public function subject() {
        return $this->hasOne('Subject', 'id', 'subject_id');
    }

    public function scopeAfterStart($query) {
        return $query->whereRaw('start < now()');
    }

    public function scopeAfterDeadline($query) {
        return $query->whereRaw('deadline > now()');
    }

    public function isAfterDeadline() {
        return $this->deadline < Carbon::now();
    }

}
