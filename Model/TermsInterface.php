<?php

namespace CP\Bundle\TermsBundle\Model;

interface TermsInterface
{
    public function getId();

    public function getVersion();

    public function getDescription();

    public function isFinal();

    public function getFinalizedAt($format = null);

    public function isLatest();

    public function getClone();

}
