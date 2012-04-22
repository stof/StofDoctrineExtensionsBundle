Using Personal translations with Symfony2 forms
==============================================

Example code to transform a Symfony2 form into an i18n Forms using Personal Translations

For example we have an Entity Product and its counterpart for Personal Translations

Product Entity
-------------

::

    namespace ExampleBundle\Entity
    
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Gedmo\Mapping\Annotation as Gedmo;
    use Symfony\Component\Validator\Constraints as Assert;
    
     /**
     * @ORM\Entity
     */
    class Product
    {
        /**
         * @var string $title
         *
         * @ORM\Column(name="title", type="string", length=255)
         */
        protected $title;
    
        /**
         * @var string $description
         *
         * @ORM\Column(name="description", type="text")
         */
        protected $description;

        /**
         * @var $translations
         *
         * @ORM\OneToMany(targetEntity="ExampleBundle\Entity\Translation\ProductTranslation", mappedBy="object", cascade={"persist", "remove"})
         */
        protected $translations;
    
        /**
         * Constructor is needed for providing an usable translations variable
         */
        public function __construct()
        {
            $this->translations = new ArrayCollection();
        }
    
        /**
         * Adds a Translation to Product
         *
         * @param ExampleBundle\Entity\Translation\ProductTranslation
         */
        public function addTranslation(Translation\ProductTranslation $t)
        {
            if (! $this->translations->contains($t)) {
                $this->translations[] = $t;
                $t->setObject($this);
            }
        }
    }
    
Product Translation
-------------------

::

    namespace ExampleBundle\Entity\Translation;

    use Doctrine\ORM\Mapping as ORM;
    use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @ORM\Entity
     * @ORM\Table(name="product_translations",
     *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
     *         "locale", "object_id", "field"
     *     })}
     * )
     */
    class ProductTranslation extends AbstractPersonalTranslation
    {
        /**
         * @ORM\ManyToOne(targetEntity="ExampleBundle\Entity\Product" inversedBy="translations")
         */
        protected $object;
    
        /**
         * @ORM\Column(name="content", type="text", length=255)
         */
        protected $content;
    }

Simple (Sonata) Form
--------------------

::

    protected function configureFormFields(FormMapper $formMapper)    {
        $formMapper
            ->with('General')
            ->add('title', 'text')
            ->add('description', 'textarea')
            ->end()
    }

To simple transform it into an i18n form use the follow files from https://gist.github.com/2437078 and
integrate it into your bundle

::

    https://gist.github.com/2437078

    <Bundle>/Form/TranslatedFieldType.php
    <Bundle>/Form/EventListener/addTranslatedFieldSubscriber.php
    <Bundle>/Resources/services.yml

Then after changing your form definition to:

::

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('title', 'translatable_field', array(
                     'field'                => 'title',
                     'personal_translation' => 'ExampleBundle\Entity\Translation\ProductTranslation',
                     'property_path'        => 'translations',
                ))
                ->add('description', 'translatable_field', array(
                     'field'                => 'description',
                     'personal_translation' => 'ExampleBundle\Entity\Translation\ProductTranslation',
                     'property_path'        => 'translations',
                ))
            ->end()
        ;
    }

you have a simple 18n form.


Validation
==========

Every field can have its own Validation, but it needs to be provided on the Personal Translation entity,
in the example you can provide a Validation with annotation like:

::

    class ProductTranslation extends AbstractPersonalTranslation
    {
        /**
         * @Assert\MinLength(limit=3, groups={"title:en", "title:nl"})
         * @Assert\MaxLength(limit=2048, groups={"description:nl"})
         * @Assert\MaxLength(limit=1024, groups={"description:en"})
         *
         * @ORM\Column(name="content", type="text", length=255)
         */
        protected $content;
    }

Adding or Changing a Language
=============================
In the file
::

    <Bundle>/Form/TranslatedFieldType.php

To change all the languages application-wide you can adjust
the variable "locales" to you needs
