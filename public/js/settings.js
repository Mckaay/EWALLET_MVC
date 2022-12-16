const toggleButtons = document.querySelectorAll('#showHideCategoriesButton');
  toggleButtons.forEach(element => {
    element.addEventListener('click', function () {
      $(element.nextElementSibling).slideToggle();
    })
  })

  const createInputNumberElement = (id) => {
    const newElem = document.createElement('input');
    newElem.type = "number";
    newElem.setAttribute('id', "categoryId");
    newElem.setAttribute('name', "categoryId");
    newElem.value = id;
    newElem.style.display = "none";

    return newElem;
  }

  const editIncomeCategoryModal = document.querySelector('#incomeCategories');

  editIncomeCategoryModal.addEventListener('show.bs.modal', function (e) {
    const formDiv = document.querySelector('.incomeCategoriesInput');
    const idCategoryInput = createInputNumberElement(e.relatedTarget.id);
    formDiv.children.newCategoryName.value = e.relatedTarget.children.categoryName.innerText;
    formDiv.appendChild(idCategoryInput);
  })

  editIncomeCategoryModal.addEventListener('hide.bs.modal', function (e) {
    const formDiv = document.querySelector('.incomeCategoriesInput');
    formDiv.removeChild(document.querySelector('#categoryId'));
  })


  const editExpenseCategoryModal = document.querySelector('#expenseCategories');

  editExpenseCategoryModal.addEventListener('show.bs.modal', function (e) {
    const formDiv = document.querySelector('.expenseCategoriesInput');
    const idCategoryInput = createInputNumberElement(e.relatedTarget.id);
    formDiv.children.newCategoryName.value = e.relatedTarget.children.categoryName.innerText;
    formDiv.children.newCategoryLimit.value = e.relatedTarget.children.categoryLimit.innerText;
    formDiv.appendChild(idCategoryInput);
  })

  editExpenseCategoryModal.addEventListener('hide.bs.modal', function (e) {
    const formDiv = document.querySelector('.expenseCategoriesInput');
    formDiv.removeChild(document.querySelector('#categoryId'));
  })

  const editPaymentMethodModal = document.querySelector('#paymentMethods');

  editPaymentMethodModal.addEventListener('show.bs.modal', function (e) {
    const formDiv = document.querySelector('.changePaymentMethodInput');
    const paymentMethodId = createInputNumberElement(e.relatedTarget.id);
    formDiv.children.changePayment.value = e.relatedTarget.children.categoryName.innerText;
    formDiv.appendChild(paymentMethodId);
  })

  editPaymentMethodModal.addEventListener('hide.bs.modal', function (e) {
    const formDiv = document.querySelector('.newPaymentMethodInput');
    formDiv.removeChild(document.querySelector('#categoryId'));
  })

