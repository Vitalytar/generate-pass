<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PasswordGeneratorController
 *
 * @package App\Controller
 */
class PasswordGeneratorController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function index(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $length = (int)$request->request->get('length');
            $includeNumbers = $request->request->get('includeNumbers') === 'on';
            $includeUppercase = $request->request->get('includeUppercase') === 'on';
            $includeLowercase = $request->request->get('includeLowercase') === 'on';
            $password = $this->generatePassword($length, $includeNumbers, $includeUppercase, $includeLowercase);

            return $this->json(['password' => $password]);
        }

        return $this->render('index.html.twig', [
            'password' => $password ?? '',
        ]);
    }

    /**
     * @throws Exception
     */
    private function generatePassword($length, $includeNumbers, $includeUppercase, $includeLowercase): string
    {
        // Define character sets
        $numbers = '0123456789';
        $uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
        $symbols = '!@#$%^&*()-_+=[]{}|;:,.<>?';
        // Initialize character set for generating password and empty password itself
        $chars = '';
        $password = '';

        if ($includeNumbers) {
            $chars .= $numbers;
            // We ensure that characters will not be duplicated in resulted string
            $selectedChar = $numbers[random_int(0, strlen($numbers) - 1)];
            $numbers = str_replace($selectedChar, '', $numbers);
            $password .= $selectedChar;
            $length -= 1;
        }

        if ($includeUppercase) {
            $chars .= $uppercaseLetters;
            $selectedChar = $uppercaseLetters[random_int(0, strlen($uppercaseLetters) - 1)];
            $uppercaseLetters = str_replace($selectedChar, '', $uppercaseLetters);
            $password .= $selectedChar;
            $length -= 1;
        }

        if ($includeLowercase) {
            $chars .= $lowercaseLetters;
            $selectedChar = $lowercaseLetters[random_int(0, strlen($lowercaseLetters) - 1)];
            $lowercaseLetters = str_replace($selectedChar, '', $lowercaseLetters);
            $password .= $selectedChar;
            $length -= 1;
        }

        if (!$includeNumbers && !$includeUppercase && !$includeLowercase) {
            $chars = $numbers . $uppercaseLetters . $lowercaseLetters . $symbols;
        }

        $chars .= $symbols;

        for ($iterator = 0; $iterator < $length; $iterator++) {
            $selectedChar = $chars[random_int(0, strlen($chars) - 1)];
            $chars = str_replace($selectedChar, '', $chars);
            $password .= $selectedChar;
        }

        return str_shuffle($password);
    }
}
