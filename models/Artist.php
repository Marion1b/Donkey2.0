<?php
class Artist{
    private ? int $id = null;

    public function __construct(
        private string $name,
        private string $picture,
        private DateTime $date,
        private string $biography,
        private string $playlist
    )
    {
    
    }
    // id
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    // name
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    // picture
    public function getPicture(): string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    // date
    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getDayToString():string{
        return $this->date->format('D j');
    }

    public function getHourToString():string{
        return $this->date->format('H');
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    // biography
    public function getBiography(): string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): void
    {
        $this->biography = $biography;
    }

    // playlist
    public function getPlaylist(): string
    {
        return $this->playlist;
    }

    public function setPlaylist(string $playlist): void
    {
        $this->playlist = $playlist;
    }

}