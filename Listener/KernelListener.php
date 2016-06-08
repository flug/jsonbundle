<?php


namespace Piktalent\Bundle\JsonLDBundle\Listener;


use Piktalent\Bundle\JsonLDBundle\Service\AggregateSchemaRoute;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class KernelListener
{

    private $request;
    private $schema;
    public  $aggregate;

    public function __construct(AggregateSchemaRoute $schemaRoute)
    {
        $this->aggregate = $schemaRoute;
    }

    public function onKernelController( $event)
    {
        $schema = $this->aggregate->findSchemaByRouteKey($request->attributes->get('_route'));


        if($schema == null){
            dump($request);die();
        }
        if($schema != null && AggregateSchemaRoute::isSchemaValid($schema)){
            $this->schema = $schema['json'];
        }
        else{
            $this->schema = null;
        }

    }
    public function onKernelRequest(GetResponseEvent $event)
    {
        if($event->isMasterRequest()) {
            $request = $event->getRequest();
            $schema  = $this->aggregate->findSchemaByRouteKey($request->attributes->get('_route'));

            if ($schema != null && AggregateSchemaRoute::isSchemaValid($schema)) {
                $this->schema = $schema['json'];
            } else {
                $this->schema = null;
            }

        }
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * @return array
     */
    public function getSchema()
    {
        return $this->schema;
    }


}