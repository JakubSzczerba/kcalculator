<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Services\Preference;

use Kcalculator\Application\DTO\PreferenceDTO;
use Kcalculator\Domain\Preference\Entity\Preference;
use Symfony\Component\Form\FormInterface;

class FormDataExtractor
{
    public function extractPreferentionDTO(FormInterface $form): PreferenceDTO
    {
        $data = $form->getData();

        if ($data instanceof Preference) {
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

        return new PreferenceDTO(
            $gender,
            $weight,
            $height,
            $age,
            $activity,
            $intentions
        );
    }
}