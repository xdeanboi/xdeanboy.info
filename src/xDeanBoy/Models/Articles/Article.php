<?php

namespace xDeanBoy\Models\Articles;

use xDeanBoy\Exceptions\ForbiddenException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Exceptions\NotFoundException;
use xDeanBoy\Models\ActiveRecordEntity;
use xDeanBoy\Models\Users\User;

class Article extends ActiveRecordEntity
{
    protected $title;
    protected $text;
    protected $authorId;
    protected $createdAt;
    protected $sectionId;
    protected $editAt;

    protected static function getTableName(): string
    {
        return 'articles';
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @param int $authorId
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->authorId = $author->getId();
    }

    /**
     * @return string
     */
    public function getAuthorNickname(): string
    {
        return User::getById($this->getAuthorId())->getNickname();
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return void
     */
    public function setCreatedAt(): void
    {
        $this->createdAt = date('y.m.d', time());
    }

    /**
     * @return string
     */
    public function getCreatedAtAsDate(): string
    {
        return date('d.m.y', strtotime($this->createdAt));
    }

    /**
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->sectionId;
    }

    /**
     * @param int $sectionId
     */
    public function setSectionId(int $sectionId): void
    {
        $this->sectionId = $sectionId;
    }

    /**
     * @param ArticlesSection $section
     * @return void
     */
    public function setSection(ArticlesSection $section): void
    {
        $this->sectionId = $section->getId();
    }

    /**
     * @return ArticlesSection
     */
    public function getSection(): ArticlesSection
    {
        return ArticlesSection::getById($this->sectionId);
    }

    /**
     * @return string
     */
    public function getSectionName(): string
    {
        return ArticlesSection::getById($this->sectionId)->getSection();
    }

    /**
     * @param string $sectionName
     * @return ArticlesSection|null
     */
    public static function getSectionByName(string $sectionName): ?ArticlesSection
    {
        $section = ArticlesSection::findOneByColumn('section', $sectionName);

        return !empty($section) ? $section : null;
    }

    /**
     * @param string $editAt
     */
    public function setEditAt(string $editAt): void
    {
        $this->editAt = $editAt;
    }

    /**
     * @return string|null
     */
    public function getEditAt(): ?string
    {
        return $this->editAt;
    }

    /**
     * @param array $articleEditFields
     * @param User $byUserEdit
     * @return $this
     * @throws ForbiddenException
     * @throws InvalidArgumentException
     */
    public function edit(array $articleEditFields, User $byUserEdit): self
    {
        if (empty($articleEditFields['title'])) {
            throw new InvalidArgumentException('Титулка не може бути пустою');
        }

        if (empty($articleEditFields['text']) || trim($articleEditFields['text']) == '') {
            throw new InvalidArgumentException('Текст статті не може бути пустим');
        }

        if (empty($byUserEdit)) {
            throw new InvalidArgumentException('Не вказаний користувач, який редагує');
        }

        if (!$byUserEdit->isAdmin()) {
            throw new ForbiddenException();
        }

        $this->setTitle($articleEditFields['title']);

        if (!empty($articleEditFields['section'])) {
            if (empty(self::getSectionByName($articleEditFields['section']))) {
                throw new InvalidArgumentException('Невідомий розділ статті');
            }

            $this->setSection(self::getSectionByName($articleEditFields['section']));
        }

        $this->setText(trim($articleEditFields['text']));
        $this->setEditAt(date('y-m-d h-m-s', time()));

        $this->save();

        return $this;

    }

    /**
     * @param array $articleCreateFields
     * @param User $byUserCreate
     * @return static
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public static function createArticle(array $articleCreateFields, User $byUserCreate): self
    {
        /**
         * Error fields
         * Error user
         * create article object
         * save in db
         * return article
         */

        if (empty($articleCreateFields['title'])) {
            throw new InvalidArgumentException('Титулка статті не може бути пустою');
        }

        if (empty($articleCreateFields['text'])) {
            throw new InvalidArgumentException('Текст статті не може бути пустим');
        }

        if (empty($articleCreateFields['section'])) {
            throw new InvalidArgumentException('Виберіть розділ');
        }

        if (empty($byUserCreate)) {
            throw new NotFoundException();
        }

        $article = new self();

        $article->setTitle($articleCreateFields['title']);
        $article->setText($articleCreateFields['text']);
        $article->setSection(self::getSectionByName($articleCreateFields['section']));
        $article->setAuthor($byUserCreate);

        $article->save();

        return $article;
    }
}