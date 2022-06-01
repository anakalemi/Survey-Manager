<?php
interface IDAO {

    public function findAll();
    public function findById($id);
    public function insert($entity);
    public function update($entity);
    public function delete($id);
}
?>