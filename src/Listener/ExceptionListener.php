<?php
/**
 *
 * This file is part of a repository on GitHub.
 *
 * (c) Riccardo De Martis <riccardo@demartis.it>
 *
 * <https://github.com/demartis>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Listener;

use App\Utils\Jttp\Jttp;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\Response;


/**
 * Description of ExceptionListener
 *
 * @author Riccardo De Martis <riccardo@demartis.it>
 */
class ExceptionListener
{
    use LoggerAwareTrait;

    public function onKernelException(ExceptionEvent $event)
    {

    }

}
