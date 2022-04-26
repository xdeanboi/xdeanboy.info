<?php

namespace xDeanBoy\Models\Users;

use xDeanBoy\Models\ActiveRecordEntity;

class Profiles extends ActiveRecordEntity
{
    protected $name;
    protected $header;
    protected $country;
    protected $about;
    protected $skills;

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'profile_users';
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->id = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->id;
    }

    /**
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $header
     * @return void
     */
    public function setHeader(?string $header): void
    {
        $this->header = $header;
    }

    /**
     * @return string|null
     */
    public function getHeader(): ?string
    {
        return $this->header;
    }

    /**
     * @param string|null $country
     * @return void
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $about
     * @return void
     */
    public function setAbout(?string $about): void
    {
        $this->about = $about;
    }

    /**
     * @return string|null
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * @param string|null $skills
     * @return void
     */
    public function setSkills(?string $skills): void
    {
        $this->skills = $skills;
    }

    /**
     * @return string|null
     */
    public function getSkills(): ?string
    {
        return $this->skills;
    }

    /**
     * @param array $userFields
     * @return void
     */
    public function editProfile(array $userFields): void
    {
        $this->setName($userFields['name']);
        $this->setHeader($userFields['header']);
        $this->setCountry($userFields['country']);
        $this->setAbout(ltrim($userFields['about']));
        $this->setSkills(ltrim($userFields['skills']));

        $this->save();
    }

    /**
     * @param User $user
     * @return void
     */
    public static function createProfile(User $user): void
    {
        $profile = new Profiles();
        $profile->setUserId($user->getId());

        $profile->save();
    }

    /**
     * @param int $userId
     * @return static|null
     */
    public static function getProfileUser(int $userId): ?self
    {
        $profile = Profiles::getById($userId);

        return !empty($profile) ? $profile : null;
    }
}