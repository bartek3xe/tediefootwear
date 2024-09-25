<?php

declare(strict_types=1);

namespace App\Enum;

enum FaqEnum: int
{
    case QUESTION_1 = 1;
    case QUESTION_2 = 2;
    case QUESTION_3 = 3;
    case QUESTION_4 = 4;
    case QUESTION_5 = 5;
    case QUESTION_6 = 6;
    case QUESTION_7 = 7;
    case QUESTION_8 = 8;
    case QUESTION_9 = 9;
    case QUESTION_10 = 10;

    public function getQuestion(): string
    {
        return match ($this) {
            self::QUESTION_1 => 'faq.question.1',
            self::QUESTION_2 => 'faq.question.2',
            self::QUESTION_3 => 'faq.question.3',
            self::QUESTION_4 => 'faq.question.4',
            self::QUESTION_5 => 'faq.question.5',
            self::QUESTION_6 => 'faq.question.6',
            self::QUESTION_7 => 'faq.question.7',
            self::QUESTION_8 => 'faq.question.8',
            self::QUESTION_9 => 'faq.question.9',
            self::QUESTION_10 => 'faq.question.10',
        };
    }

    public function getAnswer(): string
    {
        return match ($this) {
            self::QUESTION_1 => 'faq.answer.1',
            self::QUESTION_2 => 'faq.answer.2',
            self::QUESTION_3 => 'faq.answer.3',
            self::QUESTION_4 => 'faq.answer.4',
            self::QUESTION_5 => 'faq.answer.5',
            self::QUESTION_6 => 'faq.answer.6',
            self::QUESTION_7 => 'faq.answer.7',
            self::QUESTION_8 => 'faq.answer.8',
            self::QUESTION_9 => 'faq.answer.9',
            self::QUESTION_10 => 'faq.answer.10',
        };
    }
}
