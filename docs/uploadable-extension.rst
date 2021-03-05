Uploadable extension
====================

Before using this extension, read the `official Uploadable documentation`_. Once
everything is ready, use the Form component as usual. Then, after you verify the
form is valid, do the following::

    $document = new Document();
    $form = $this->createFormBuilder($document)
        ->add('name')
        ->add('myFile')
        ->getForm()
    ;

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($document);

        $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

        // Here, "getMyFile" returns the "UploadedFile" instance that the form bound in your $myFile property
        $uploadableManager->markEntityToUpload($document, $document->getMyFile());

        $em->flush();

        return $this->redirect($this->generateUrl('...'));
    }

    return $this->render('...', array('form' => $form->createView()));

And that's it. The Uploadable extension handles the rest of the stuff. Remember
to read its documentation!

.. _`official Uploadable documentation`: https://github.com/Atlantic18/DoctrineExtensions/blob/v2.4.x/doc/uploadable.md
