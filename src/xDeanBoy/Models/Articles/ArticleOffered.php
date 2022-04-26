<?php

namespace xDeanBoy\Models\Articles;

use xDeanBoy\Exceptions\ForbiddenException;
use xDeanBoy\Exceptions\InvalidArgumentException;
use xDeanBoy\Models\ActiveRecordEntity;
use xDeanBoy\Models\Users\User;

class ArticleOffered extends ActiveRecordEntity
{
    protected $sectionId;
    protected $theme;
    protected $userId;
    protected $createdAt;

    /**
     * @return int
     */
    public function getSectionId():int
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
     * @return ArticlesSection
     */
    public function getSection(): ArticlesSection
    {
        return ArticlesSection::getById($this->sectionId);
    }

    /**
     * @param string $section
     * @return void
     */
    public function setSection(string $section):void
    {
        $articleSection = ArticlesSection::findOneByColumn('section', $section);

        if (!empty($articleSection)) {
            $this->sectionId = $articleSection->getId();
        }
    }

    /**
     * @return string
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     */
    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return User::getById($this->getUserId());
    }

    public function setUser(User $user):void
    {
        $this->userId = $user->getId();
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'articles_offered';
    }

    /**
     * @param array $offeredArticle
     * @param User $user
     * @return static
     * @throws ForbiddenException
     * @throws InvalidArgumentException
     */
    public static function createOffering(array $offeredArticle, User $user): self
    {
        if (empty($offeredArticle['offeringSection'])) {
            throw new InvalidArgumentException('Виберіть розділ');
        }

        if (empty($offeredArticle['offeringTheme'])) {
            throw new InvalidArgumentException('На яку тему?');
        }

        if (empty($user)) {
            throw new ForbiddenException('Авторизуйтеся');
        }

        $articleOffered = new self();

        $articleOffered->setSection($offeredArticle['offeringSection']);
        $articleOffered->setTheme($offeredArticle['offeringTheme']);
        $articleOffered->setUser($user);

        $articleOffered->save();

        return $articleOffered;
    }
}