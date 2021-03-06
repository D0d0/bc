<?php

/**
 * Description of Group
 *
 * @author Jozef Dúc
 */
class Group extends Eloquent {

    use \Venturecraft\Revisionable\RevisionableTrait;

    const CREATED = 0;
    const APPROVED = 1;

    protected $table = 'groups';
    protected $fillable = array(
        'name',
        'subject_id',
        'created_by',
        'task_id',
        'state',
        'created_at',
        'updated_at'
    );

    public function scopeCreated($query) {
        return $query->where('state', '=', $this::CREATED);
    }

    public function scopeApproved($query) {
        return $query->where('state', '=', $this::APPROVED);
    }

    public function members() {
        return User::whereIn('id', GroupMembers::where('group_id', '=', $this->id)->select('user_id')->get()->toArray());
    }

    public function isMember($id) {
        return GroupMembers::where('group_id', '=', $this->id)->where('user_id', '=', $id)->count();
    }

    public function task() {
        return $this->hasOne('Task', 'id', 'task_id');
    }

    public function created_by() {
        return $this->hasOne('User', 'id', 'created_by');
    }

    public function isApproved() {
        return $this->state == $this::APPROVED;
    }

}
