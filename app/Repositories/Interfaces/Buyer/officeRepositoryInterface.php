<?php
namespace App\Repositories\Interfaces\Buyer;

use App\Repositories\Interfaces\baseRepositoryInterface;
use App\User;

interface officeRepositoryInterface extends baseRepositoryInterface {

    public function update($id,$data);
    public function findAllWhereUserBelongsTo($userId);
    public function findAllWorkersAffiliatedTo($id);
    public function findEligibleWorkersForOffice($id,User $userCreating);
    public function addWorkerToOffice($id,$userId);

}