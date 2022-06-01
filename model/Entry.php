<?php

class Entry implements JsonSerializable
{

    public $id;
    public $user_id;
    public $survey_id;

    /**
     * @param $id
     * @param $user_id
     * @param $survey_id
     */
    public function __construct($id, $user_id, $survey_id)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->survey_id = $survey_id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getSurveyId()
    {
        return $this->survey_id;
    }

    /**
     * @param mixed $survey_id
     */
    public function setSurveyId($survey_id): void
    {
        $this->survey_id = $survey_id;
    }

    public function jsonSerialize()
    {
        return (object)get_object_vars($this);
    }

    /**
     * @return array
     */
    public function getAnswers(): array
    {
        $entryDAO = new EntryDAO();
        return $entryDAO ->getEntryAnswers($this -> getId());
    }

}