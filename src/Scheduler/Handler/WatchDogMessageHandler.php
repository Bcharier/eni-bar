<?php

namespace App\Scheduler\Handler;

use App\Scheduler\Message\WatchDogMessage;
use App\Service\UpdateStateService;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class WatchDogMessageHandler
{
  public function __construct(private SortieRepository $sortieRepository, private EntityManagerInterface $manager, private UpdateStateService $updateStateService)
  {
  }

  public function __invoke(WatchDogMessage $message)
  {
    // Handling OnGoing Sorties
    $res = $this->updateStateService->updateOngoingSorties();
    print_r($this->formatLogMessage($res)."\n\r");

    // Handling Passed Sorties

    // Handling 
  }

  public function formatLogMessage(int $nbLines)
  {
    $logMessage = $nbLines;
    switch($nbLines) {
      case 0:
      case 1:
        $logMessage = 'sortie a été mise à jour';
      break;
      default:
        $logMessage = "sorties ont été mises à jour";
      break;

    }
    date_default_timezone_set('Europe/Paris');
    $logMessage = "[".date("Y-m-d h:i")."] ".$nbLines . " " . $logMessage.' de l`état "Publiée" vers "En cours"';
    return $logMessage;
  }
}