<?php


namespace App\Representation;


use App\Repository\PhoneRepository;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PhonesRepresentation
{
    const GROUP = 'phones_list';
    /**
     * @var PhoneRepository
     */
    private PhoneRepository $phoneRepository;
    /**
     * @var ArrayTransformerInterface
     */
    private ArrayTransformerInterface $arrayTransformer;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;
    private \JMS\Serializer\SerializationContext $context;

    /**
     * PhonesRepresentation constructor.
     * @param PhoneRepository $phoneRepository
     * @param ArrayTransformerInterface $arrayTransformer
     * @param SerializationContextFactoryInterface $factory
     * @param RequestStack $requestStack
     */
    public function __construct(PhoneRepository $phoneRepository,
                                ArrayTransformerInterface $arrayTransformer,
                                SerializationContextFactoryInterface $factory,
                                RequestStack $requestStack)
    {

        $this->phoneRepository = $phoneRepository;
        $this->arrayTransformer = $arrayTransformer;
        $this->requestStack = $requestStack;
        $this->context = $factory->createSerializationContext();
        $this->context->setGroups(self::GROUP);
    }

    public function constructPhoneRepresentation($order = 'asc', $limit = 10, $page = 1)
    {
        $phoneCounter = $this->phoneRepository->countPhones();
        $maxPage = $phoneCounter / $limit;
        $offset = ($page - 1) * $limit;

        $pager = $this->phoneRepository->findBy([], ['id' => strtoupper($order)], $limit, $offset);
        $normalized = $this->arrayTransformer->toArray($pager,$this->context);

        $phonesRepresentation = new PaginatedRepresentation(
            new CollectionRepresentation($normalized),
            'app_list_phones',
            [],
            $page,
            $limit,
            $maxPage,
            'page',
            'limit',
            true,
            $phoneCounter
        );
        return $phonesRepresentation;
    }
}