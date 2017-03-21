<?php

namespace AppBundle\Entity\Statistic;

use AppBundle\Validator\Constraints as CustomAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Statistical compare data.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RepoScoreStatisticRepository")
 * @ORM\Table(name="repos_score_statistic")
 */
class RepoScoreStatistic
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @CustomAssert\GitHubUrlConstraint
     * @ORM\Column(name="url_first", type="string", length=500, nullable=false)
     */
    private $urlFirst;

    /**
     * @CustomAssert\GitHubUrlConstraint
     * @ORM\Column(name="url_second", type="string", length=500, nullable=false)
     */
    private $urlSecond;

    /**
     * @var int
     *
     * @ORM\Column(name="score_first", type="smallint", nullable=false)
     */
    private $scoreFirst;

    /**
     * @var int
     *
     * @ORM\Column(name="score_second", type="smallint", nullable=false)
     */
    private $scoreSecond;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set urlFirst
     *
     * @param string $urlFirst
     *
     * @return RepoScoreStatistic
     */
    public function setUrlFirst($urlFirst)
    {
        $this->urlFirst = $urlFirst;

        return $this;
    }

    /**
     * Get urlFirst
     *
     * @return string
     */
    public function getUrlFirst()
    {
        return $this->urlFirst;
    }

    /**
     * Set urlSecond
     *
     * @param string $urlSecond
     *
     * @return RepoScoreStatistic
     */
    public function setUrlSecond($urlSecond)
    {
        $this->urlSecond = $urlSecond;

        return $this;
    }

    /**
     * Get urlSecond
     *
     * @return string
     */
    public function getUrlSecond()
    {
        return $this->urlSecond;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return RepoScoreStatistic
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set scoreFirst
     *
     * @param integer $scoreFirst
     *
     * @return RepoScoreStatistic
     */
    public function setScoreFirst($scoreFirst)
    {
        $this->scoreFirst = $scoreFirst;

        return $this;
    }

    /**
     * Get scoreFirst
     *
     * @return integer
     */
    public function getScoreFirst()
    {
        return $this->scoreFirst;
    }

    /**
     * Set scoreSecond
     *
     * @param integer $scoreSecond
     *
     * @return RepoScoreStatistic
     */
    public function setScoreSecond($scoreSecond)
    {
        $this->scoreSecond = $scoreSecond;

        return $this;
    }

    /**
     * Get scoreSecond
     *
     * @return integer
     */
    public function getScoreSecond()
    {
        return $this->scoreSecond;
    }
}
