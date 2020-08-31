<?php


namespace App\Request\ParamConverter;


use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiConverter implements ParamConverterInterface
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $manager)
    {

        $this->serializer = $serializer;
        $this->manager = $manager;
    }
    public function apply(Request $request, ParamConverter $configuration)
    {
        $options = [];
        if($request->attributes->has('id')){
            $object = $this->manager->getRepository($configuration->getClass())->find($request->attributes->get('id'));
            $options[AbstractNormalizer::OBJECT_TO_POPULATE] = $object;
        }
        $user = $this->serializer->deserialize($request->getContent(),$configuration->getClass(), 'json');

        $request->attributes->set($configuration->getName(), $user);
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getName() === "user";
    }
}