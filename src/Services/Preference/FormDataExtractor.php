<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Services\Preference;

use Kcalculator\DTO\PreferentionDTO;
use Kcalculator\Entity\UserPreferention;
use Symfony\Component\Form\FormInterface;

class FormDataExtractor
{
    public function extractPreferentionDTO(FormInterface $form): PreferentionDTO
    {
        $data = $form->getData();

        if ($data instanceof UserPreferention) {
            $gender = $data->getGender();
            $weight = $data->getWeight();
            $height = (string)$data->getHeight();
            $age = (string)$data->getAge();
            $activity = $data->getActivity();
            $intentions = $data->getIntentions();
        } else {
            $gender = $data['gender'];
            $weight = $data['weight'];
            $height = $data['height'];
            $age = $data['age'];
            $activity = $data['activity'];
            $intentions = $data['intentions'];
        }

        return new PreferentionDTO(
            $gender,
            $weight,
            $height,
            $age,
            $activity,
            $intentions
        );
    }
}