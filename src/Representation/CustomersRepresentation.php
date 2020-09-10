<?php


namespace App\Representation;


use App\Entity\User;
use App\Repository\CustomerRepository;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\RequestStack;

class CustomersRepresentation
{

    const GROUP = 'customers_list';

    /**
     * @var ArrayTransformerInterface
     */
    private ArrayTransformerInterface $arrayTransformer;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;
    private SerializationContext $context;
    /**
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * PhonesRepresentation constructor.
     * @param CustomerRepository $customerRepository
     * @param ArrayTransformerInterface $arrayTransformer
     * @param SerializationContextFactoryInterface $factory
     * @param RequestStack $requestStack
     */
    public function __construct(CustomerRepository $customerRepository,
                                ArrayTransformerInterface $arrayTransformer,
                                SerializationContextFactoryInterface $factory,
                                RequestStack $requestStack)
    {

        $this->arrayTransformer = $arrayTransformer;
        $this->requestStack = $requestStack;
        $this->context = $factory->createSerializationContext();
        $this->context->setGroups(self::GROUP);
        $this->customerRepository = $customerRepository;
    }
    public function constructPhoneRepresentation(User $user,$order = 'asc', $limit = 10, $page = 1)
    {

        $customerCounter = $this->customerRepository->count(["user"=>$user]);
        $maxPage = ceil($customerCounter / $limit);
        $offset = ($page - 1) * $limit;

        $pager = $this->customerRepository->findBy(['user'=>$user], ['id' => strtoupper($order)], $limit, $offset);
        $normalized = $this->arrayTransformer->toArray($pager,$this->context);

        return new PaginatedRepresentation(
            new CollectionRepresentation($normalized),
            'app_list_customers',
            [],
            $page,
            $limit,
            $maxPage,
            'page',
            'limit',
            true,
            $customerCounter
        );
    }
}