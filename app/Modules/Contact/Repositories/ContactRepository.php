<?php namespace App\Modules\Contact\Repositories;

use App\Modules\Contact\Entities\Contact;

class ContactRepository
{
    // model property on class instances
    protected $contact;

    // Constructor to bind model to repo
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    // Get all instances of model
    public function all()
    {
        return $this->contact->all();
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->contact->destroy($id);
    }

}
