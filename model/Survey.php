<?php

class Survey implements JsonSerializable
{
    const NOT_PUBLISHED = 0;
    const PUBLISHED = 1;

    private $id;
    private $title;
    private $user_id;
    private $published;

    /**
     * @param $id
     * @param $title
     * @param $user_id
     * @param $published
     */
    public function __construct($id, $title, $user_id, $published)
    {
        $this->id = $id;
        $this->title = $title;
        $this->user_id = $user_id;
        $this->published = $published;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published): void
    {
        $this->published = $published;
    }

    public function jsonSerialize()
    {
        return (object)get_object_vars($this);
    }

    /**
     * @return array
     */
    public function getQuestions(): array
    {
        $surveyDao = new SurveyDAO();
        return $surveyDao->getSurveysQuestions($this->getId());
    }

}