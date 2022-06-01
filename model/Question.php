<?php

class Question implements JsonSerializable
{
    const TYPE_TEXT = 0;
    const TYPE_NUMBER = 1;
    const TYPE_DATE = 2;

    public $id;
    public $description;
    public $type;
    public $survey_id;

    /**
     * @param $id
     * @param $description
     * @param $type
     * @param $survey_id
     */
    public function __construct($id, $description, $type, $survey_id)
    {
        $this->id = $id;
        $this->description = $description;
        $this->type = $type;
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
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
    public function setSurveyId($survey_id)
    {
        $this->survey_id = $survey_id;
    }

    public function jsonSerialize()
    {
        return (object)get_object_vars($this);
    }

}
