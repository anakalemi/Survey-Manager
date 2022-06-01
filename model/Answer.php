<?php

class Answer implements JsonSerializable
{
    public $id;
    public $entry_id;
    public $question_id;
    public $content;

    /**
     * @param $id
     * @param $entry_id
     * @param $question_id
     * @param $content
     */
    public function __construct($id, $entry_id, $question_id, $content)
    {
        $this->id = $id;
        $this->entry_id = $entry_id;
        $this->question_id = $question_id;
        $this->content = $content;
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
    public function getEntryId()
    {
        return $this->entry_id;
    }

    /**
     * @param mixed $entry_id
     */
    public function setEntryId($entry_id): void
    {
        $this->entry_id = $entry_id;
    }

    /**
     * @return mixed
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * @param mixed $question_id
     */
    public function setQuestionId($question_id): void
    {
        $this->question_id = $question_id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    public function jsonSerialize()
    {
        return (object)get_object_vars($this);
    }

}
