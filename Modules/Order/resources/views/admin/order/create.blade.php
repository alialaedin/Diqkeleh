<x-layouts.master title="سفارشات" id="app">

  <div class="page-header d-print-none">
    <x-breadcrumb>
      <x-breadcrumb-item title="سفارشات" :route="route('admin.orders.index')" />
      <x-breadcrumb-item title="ثبت سفارش جدید" />
    </x-breadcrumb>
  </div>

  <x-card title="اطلاعات مشتری" class="d-print-none">

    <div class="row">

      <div class="col-12 col-xl-3">
        <fieldset class="form-group">
          <label>انتخاب مشتری <span class="text-danger">&starf;</span></label>
          <input type="text" class="form-control" v-model="customerMobile" :disabled="isCustomerMobileInputDisabled" />
        </fieldset>
        <div v-if="showCancleCustomerButton" class="d-flex" style="gap: 8px">
          <button class="btn btn-sm btn-danger" @click="removeCustomer">لغو</button>
          <span class="badge badge-info">کیف پول : @{{ customer.wallet.balance.toLocaleString() }} تومان</span>
        </div>
      </div>

      <template v-if="customer">

        <div class="col-12 col-xl-3">
          <fieldset class="form-group">
            <label>نام <span class="text-danger">&starf;</span></label>
            <input type="text" class="form-control" v-model="customer.first_name" required />
          </fieldset>
        </div>

        <div class="col-12 col-xl-3">
          <fieldset class="form-group">
            <label>نام خانوادگی <span class="text-danger">&starf;</span></label>
            <input type="text" class="form-control" v-model="customer.last_name" required />
          </fieldset>
        </div>

        <div class="col-12 col-xl-3">
          <fieldset class="form-group">
            <label for="address">انتخاب آدرس <span class="text-danger">&starf;</span></label>
            <multiselect v-model="address" :options="addresses" placeholder="انتخاب آدرس" track-by="id"
              :show-labels="false" label="address" class="custom-multiselect" />
          </fieldset>
          <button data-target="#new-address-modal" data-toggle="modal" class="btn btn-sm btn-primary"
            @click="openAddressModal">آدرس جدید</button>
        </div>

      </template>

    </div>

  </x-card>

  <x-card title="اطلاعات سفارش" class="d-print-none">

    <x-row>

      <x-col xl="3">
        <x-form-group>
          <x-label text="تخفیف روی سفارش (تومان)" />
          <x-input v-model="discountOnOrder" type="text" name="discountOnOrder" @input="formatNumber($event)" />
        </x-form-group>
      </x-col>

      <x-col xl="3">
        <x-form-group>
          <x-label text="انتخاب پیک" />
          <multiselect v-model="courier" :options="couriers" placeholder="انتخاب پیک" track-by="id" :show-labels="false"
            label="full_name" class="custom-multiselect" />
        </x-form-group>
      </x-col>

      <x-col xl="3">
        <x-form-group>
          <x-label text="هزینه ارسال (تومان)" />
          <x-input v-model="shippingAmount" type="text" name="shippingAmount" @input="formatNumber($event)" />
        </x-form-group>
      </x-col>

      <x-col>
        <x-form-group>
          <x-label text="یادداشت" />
          <x-textarea rows="3" name="description" v-model="description" />
        </x-form-group>
      </x-col>

    </x-row>

  </x-card>

  <x-card title="انتخاب محصولات" class="d-print-none">

    <div class="row">
      <div class="col-12 col-xl-3">
        <fieldset class="form-group">
          <label>دسته بندی</label>
          <multiselect v-model="category" class="custom-multiselect" label="title" placeholder="انتخاب دسته بندی"
            :options="categories" :show-labels="false" />
        </fieldset>
      </div>
      <div v-if="categoryProducts.length" class="col-12 col-xl-3">
        <fieldset class="form-group">
          <label>محصول</label>
          <multiselect v-model="product" class="custom-multiselect" placeholder="انتخاب محصول"
            :options="categoryProducts" :custom-label="productCustomLabel" :show-labels="false" @select="addProduct" />
        </fieldset>
      </div>
    </div>

    <div class="row mt-5">
      <div class="col-12">
        <template v-if="selectedProducts.length">
          <x-table>
            <x-slot name="thead">
              <tr>
                <th>ردیف</th>
                <th>دسته بندی</th>
                <th>عنوان</th>
                <th>قیمت واحد (تومان)</th>
                <th>تخفیف واحد (تومان)</th>
                <th>موجودی</th>
                <th>تعداد</th>
                <th>قیمت نهایی (تومان)</th>
                <th>عملیات</th>
              </tr>
            </x-slot>
            <x-slot name="tbody">
              <tr v-for="(product, index) in selectedProducts" :key="index">
                <td class="font-weight-bold">@{{ index + 1 }}</td>
                <td>@{{ product.category.title }}</td>
                <td>@{{ product.title }}</td>
                <td>
                  <input type="text" v-model="product.price" class="form-control text-center"
                    @input="formatNumber($event)" />
                </td>
                <td>
                  <input type="text" v-model="product.discount" class="form-control text-center"
                    @input="formatNumber($event)" />
                </td>
                <td>@{{ product.balance }}</td>
                <td>
                  <button type="button" @click="decreaseCartQuantity(product)" :disabled="product.quantity == 1"
                    class="px-1 bg-none" style="border: 1px solid #e9ebfa !important;vertical-align: top">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      viewBox="0 0 16 16" class="bi bi-dash">
                      <path data-v-59b5239a="" d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"></path>
                    </svg>
                  </button>
                  <span class="px-2">@{{ product.quantity }}</span>
                  <button type="button" @click="increaseCartQuantity(product)"
                    :disabled="product.quantity == product.balance" class="px-1 bg-none"
                    style="border: 1px solid #e9ebfa !important;vertical-align: top">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      viewBox="0 0 16 16" class="bi bi-plus">
                      <path data-v-5558c195=""
                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z">
                      </path>
                    </svg>
                  </button>
                </td>
                <td>@{{ productFinalPrices[index].toLocaleString() }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-danger" @click="removeProduct(index)">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
            </x-slot>
          </x-table>
        </template>
      </div>
    </div>

  </x-card>

  <x-row v-if="selectedProducts.length" class="justify-content-center d-print-none">
    <x-col md="8" lg="6" xl="4">
      <x-card>
        <div class="row flex-column" style="gap: 8px">

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">مجموع قیمت کالا ها :</b>
            <span class="fs-12">@{{ finalPrices.baseAmount.toLocaleString() }} تومان</span>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">جمع تخفیف کالا ها :</b>
            <span class="fs-12">@{{ finalPrices.discountAmount.toLocaleString() }} تومان</span>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">تخفیف روی سفارش :</b>
            <span class="fs-12">@{{ finalPrices.discountOnOrder.toLocaleString() }} تومان</span>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">هزینه ارسال :</b>
            <span class="fs-12">@{{ finalPrices.shippingAmount.toLocaleString() }} تومان</span>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">مبلغ قابل پرداخت :</b>
            <span class="fs-12 text-primary fs-15">@{{ finalPrices.finalAmount.toLocaleString() }} تومان</span>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">مبلغ کارت به کارت (تومان) :</b>
            <input v-model="cardByCardAmount" type="text" class="text-left form-control" style="width: auto"
              @input="formatNumber($event)" />
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">مبلغ پرداخت نقدی (تومان) :</b>
            <input v-model="cashAmount" type="text" class="text-left form-control" style="width: auto"
              @input="formatNumber($event)" />
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">پرداخت از پوز (تومان) :</b>
            <input v-model="posAmount" type="text" class="text-left form-control" style="width: auto"
              @input="formatNumber($event)" />
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">پرداخت از کیف پول (تومان) :</b>
            <input v-model="fromWalletAmount" type="text" class="text-left form-control" style="width: auto"
              @input="formatNumber($event)" />
          </div>

        </div>
      </x-card>
    </x-col>

    <x-col class="text-center">
      <button type="button" @click="store" class="btn btn-success mx-2" :disabled="isStoreButtonDisabled">ثبت
        سفارش</button>
      <button type="button" @click="print" class="btn btn-purple mx-2">پرینت</button>
    </x-col>

    <div style="margin-top: 80px"></div>

  </x-row>

  <div v-if="selectedProducts.length" class="table-responsive d-none d-print-block">
    <div class="dataTables_wrapper dt-bootstrap4 no-footer">
      <div class="row">
        <table class="table table-vcenter table-striped text-nowrap table-bordered text-center border-bottom">
          <thead class="thead-dark">
            <tr>
              <th>ردیف</th>
              <th>عنوان</th>
              <th>قیمت</th>
              <th>تخفیف</th>
              <th>تعداد</th>
              <th>جمع کل</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(product, index) in selectedProducts" :key="index">
              <td class="font-weight-bold">@{{ index + 1 }}</td>
              <td>@{{ product.title }}</td>
              <td>@{{ Number(product.price?.replace(/,/g, "") ?? 0).toLocaleString() }}</td>
              <td>@{{ Number(product.discount?.replace(/,/g, "") ?? 0).toLocaleString() }}</td>
              <td>@{{ product.quantity }}</td>
              <td>@{{ productFinalPrices[index].toLocaleString() }}</td>
            </tr>
            <tr>
              <td colspan="2">جمع کل</td>
              <td>@{{ finalPrices.baseAmount.toLocaleString() }}</td>
              <td>@{{ finalPrices.discountAmount.toLocaleString() }}</td>
              <td>@{{ selectedProducts.reduce((sum, product) => sum + product.quantity, 0) }}</td>
              <td>@{{ finalPrices.finalAmount.toLocaleString() }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <x-modal id="new-address-modal" title="ثبت آدرس جدید">
    <x-row>
      <x-col>
        <x-form-group>
          <x-label :is-required="true" text="نام" />
          <x-input type="text" name="first_name" v-model="newAddress.first_name" />
        </x-form-group>
      </x-col>
      <x-col>
        <x-form-group>
          <x-label :is-required="true" text="نام خانوادگی" />
          <x-input type="text" name="last_name" v-model="newAddress.last_name" />
        </x-form-group>
      </x-col>
      <x-col>
        <x-form-group>
          <x-label :is-required="true" text="شماره همراه" />
          <x-input type="text" name="mobile" v-model="newAddress.mobile" />
        </x-form-group>
      </x-col>
      <x-col>
        <x-form-group>
          <x-label :is-required="true" text="آدرس" />
          <x-textarea rows="3" name="address" v-model="newAddress.address" />
        </x-form-group>
      </x-col>
      <x-col>
        <button type="button" class="btn btn-sm btn-primary btn-block" @click="storeNewAddress">ثبت و ذخیره</button>
      </x-col>
    </x-row>
  </x-modal>

  @push('scripts')

    <script src="{{ asset('assets/vue/vue3/vue.global.prod.js') }}"></script>
    <script src="{{ asset('assets/vue/multiselect/vue-multiselect.umd.min.js') }}"></script>
    <script src="{{ asset('assets/vue/treeselect/vue-treeselect.umd.min.js') }}"></script>

    <script>

      const { createApp } = Vue;
      const closeModal = (selector) => $(selector).modal('hide');

      createApp({
        components: {
          'multiselect': window['vue-multiselect'].default,
        },
        data() {
          return {
            customerSearchUrl: @json(route('admin.customers.order-search')),
            createAddressUrl: @json(route('admin.addresses.store')),
            couriers: @json($couriers),
            courier: null,
            customerMobile: '',
            customer: null,
            addresses: [],
            address: null,
            discountOnOrder: null,
            shippingAmount: @json(number_format($defaultShippingAmount)),
            description: '',
            showCancleCustomerButton: false,
            isStoreButtonDisabled: false,
            abortController: null,
            debounceTimeout: null,
            categories: @json($categories),
            category: null,
            allProducts: @json($products),
            categoryProducts: [],
            selectedProducts: [],
            product: null,
            cardByCardAmount: null,
            cashAmount: null,
            posAmount: null,
            fromWalletAmount: null,
            newAddress: {
              first_name: null,
              last_name: null,
              mobile: null,
              address: null,
              customer_id: null,
            }
          }
        },
        methods: {

          formatNumber(event) {
            if (event.which >= 37 && event.which <= 40) return;
            event.target.value = event.target.value.replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
          },
          showValidationError(errors) {

            console.log(errors);

            const list = document.createElement('ul');
            list.className = 'list-group';

            for (const key in errors) {
              if (errors.hasOwnProperty(key)) {
                const errorsArray = errors[key];
                errorsArray.forEach((errorMessage) => {
                  const listItem = document.createElement('li');
                  listItem.className = 'list-group-item';
                  listItem.textContent = errorMessage;
                  list.appendChild(listItem);
                });
              }
            }

            Swal.fire({
              title: "<b>خطا های زیر رخ داده است</b>",
              html: list.outerHTML,
              icon: "error",
              confirmButtonText: "بستن",
            });
          },
          popup(type, title, message) {
            Swal.fire({
              title: title,
              text: message,
              icon: type,
              confirmButtonText: "بستن",
            });
          },
          popupWithConfirmCallback(type, title, message, confirmButtonText, isConfirmedCallback) {
            Swal.fire({
              title: title,
              text: message,
              icon: type,
              confirmButtonText: confirmButtonText,
              showDenyButton: true,
              denyButtonText: "انصراف",
            }).then((result) => {
              if (result.isConfirmed) isConfirmedCallback();
            });
          },

          async request(url, method, data = null, onSuccessRequest) {

            if (this.abortController) {
              this.abortController.abort();
            }
            this.abortController = new AbortController();

            let options = {
              method,
              headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': @json(csrf_token())
              },
              signal: this.abortController.signal,
            };

            if (data === null) {
              options.headers['Content-Type'] = 'application/json';
            } else if (data instanceof FormData) {
              options.body = data;
            } else {
              options.body = JSON.stringify(data);
              options.headers['Content-Type'] = 'application/json';
            }

            const response = await fetch(url, options);
            const result = await response.json();

            if (!response.ok) {
              switch (response.status) {
                case 422:
                  this.showValidationError(result.errors);
                  break;
                case 404:
                  this.popup('error', 'خطای 404', 'چنین چیزی وجود ندارد');
                  break;
                case 500:
                  this.popup('error', 'خطای سرور', result.message);
                  break;
                case 400:
                  this.popup('error', 'خطای سرور', result.message);
                  break;
                default:
                  this.popup('error', 'خطای نا شناخته');
                  break;
              }
              return;
            }

            onSuccessRequest(result);
          },
          searchCustomer() {
            const url = `${this.customerSearchUrl}?mobile=${encodeURIComponent(this.customerMobile)}`;
            this.request(url, 'GET', null, async (result) => {
              this.showCancleCustomerButton = true;
              if (result.data.customer?.id) {
                this.customer = result.data.customer;
              }
            });
          },
          removeCustomer() {

            this.customer = null;
            this.customerMobile = '';
            this.showCancleCustomerButton = false;
            this.address = null;

            this.newAddress.first_name = null;
            this.newAddress.last_name = null;
            this.newAddress.mobile = null;
            this.newAddress.address = null;
            this.newAddress.customer_id = null;

          },

          productCustomLabel({ id, title, store }) {
            return `${title} | موجودی: ${store.balance}`;
          },
          addProduct() {

            const selected = this.product;
            const alreadyExists = this.selectedProducts?.some(p => p.id === selected.id);
            if (alreadyExists) {
              this.popup('warning', 'اخطار', 'این محصول از قبل انتخاب شده است');
              return;
            }

            const balance = selected.store.balance;
            const price = selected.unit_price.toLocaleString();
            const discount = selected.discount_amount.toLocaleString();
            const quantity = 1;
            const newProduct = { ...selected, price, discount, balance, quantity };

            if (balance === 0) {
              this.popupWithConfirmCallback(
                'warning',
                'اخطار',
                'محصول ناموجود میباشد! آیا میخواهید آن را به لیست اضافه کنید ؟',
                'اضافه کن',
                () => this.selectedProducts.push(newProduct)
              );
              return;
            }

            this.selectedProducts.push(newProduct);

          },
          removeProduct(index) {
            if (index !== -1) {
              this.selectedProducts.splice(index, 1);
            }
          },

          increaseCartQuantity(product) {
            if (product.quantity < product.balance) {
              product.quantity = product.quantity + 1;
            }
          },
          decreaseCartQuantity(product) {
            if (product.quantity > 1) {
              product.quantity = product.quantity - 1;
            }
          },

          storeNewAddress() {
            closeModal('#new-address-modal');
            this.request(this.createAddressUrl, 'POST', this.newAddress, async (result) => {
              if (result.success) {
                this.popup('success', null, result.message);
                this.addresses.unshift(result.data.address);
                this.address = this.addresses[0];
              }
            });
          },
          print() {
            window.print();
          },
          store() {

            const fromWalletAmount = Number(this.fromWalletAmount?.replace(/,/g, "") ?? 0);

            if (this.customer == null) {
              this.popup('warning', 'خطای اعتبار سنجی', 'ابتدا مشتری را انتخاب کنید');
              return;
            }

            if (fromWalletAmount > 0 && fromWalletAmount > this.customer.wallet.balance) {
              this.popup('warning', 'خطای اعتبار سنجی', 'میزان پرداختی از کیف پول بیشتر از موجودی کاربر است');
              return;
            }

            if (this.customer.first_name.trim().length == 0) {
              this.popup('warning', 'خطای اعتبار سنجی', 'نام مشتری وارد نشده است');
              return;
            }

            if (this.customer.last_name.trim().length == 0) {
              this.popup('warning', 'خطای اعتبار سنجی', 'نام خانوادگی مشتری وارد نشده است');
              return;
            }

            if (this.address == null) {
              this.popup('warning', 'خطای اعتبار سنجی', 'ابتدا یک آدرس انتخاب کنید');
              return;
            }

            this.isStoreButtonDisabled = true;

            const url = @json(route('admin.orders.store'));
            const data = {
              customer_id: this.customer.id,
              address_id: this.address.id,
              courier_id: this.courier?.id ?? null,
              from_wallet_amount: fromWalletAmount,
              description: this.description ?? null,
              first_name: this.customer.first_name,
              last_name: this.customer.last_name,
              products: [],
            };

            data['shipping_amount'] = Number(this.shippingAmount?.toString().replace(/,/g, "") ?? 0);
            data['discount_amount'] = Number(this.discountAmount?.toString().replace(/,/g, "") ?? 0);
            data['cash_amount'] = Number(this.cashAmount?.toString().replace(/,/g, "") ?? 0);
            data['card_by_card_amount'] = Number(this.cardByCardAmount?.toString().replace(/,/g, "") ?? 0);
            data['pos_amount'] = Number(this.posAmount?.toString().replace(/,/g, "") ?? 0);

            this.selectedProducts?.forEach(product => {
              data.products.push({
                id: product.id,
                quantity: product.quantity,
                amount: Number(product.price?.toString().replace(/,/g, "") ?? 0),
                discount_amount: Number(product.discount?.toString().replace(/,/g, "") ?? 0),
              });
            });

            try {
              this.request(url, 'POST', data, async (result) => {
                console.log(result);
                this.popup('success', '', result.message);
                // setTimeout(() => window.print(), 1000);
              });
            } catch (error) {
              console.log(error);
            } finally {
              this.isStoreButtonDisabled = false;
            }

          },
        },
        watch: {
          isCustomerMobileInputDisabled(disabled) {
            if (disabled) {
              this.searchCustomer();
            }
          },
          customer(customer) {
            this.addresses = customer == null ? [] : customer.addresses;
            if (customer) {
              this.newAddress.first_name = customer.first_name;
              this.newAddress.last_name = customer.last_name;
              this.newAddress.mobile = customer.mobile;
              this.newAddress.customer_id = customer.id;
            }
          },
          addresses(newVal) {
            if (newVal == null) {
              this.address = null;
            }
          },
          category(category) {
            if (category) {
              this.categoryProducts = this.allProducts.filter(p => p.category_id == category.id);
            }
          },
          'customer.first_name'(value) {
            if (value.trim().length > 0) {
              this.newAddress.first_name = value;
            }
          },
          'customer.last_name'(value) {
            if (value.trim().length > 0) {
              this.newAddress.last_name = value;
            }
          },
        },
        computed: {
          isCustomerMobileInputDisabled() {
            return this.customerMobile.length == 11;
          },
          productFinalPrices() {
            return this.selectedProducts.map(product => {
              const price = Number(product.price?.replace(/,/g, '')) || 0;
              const discount = Number(product.discount?.replace(/,/g, '')) || 0;
              return (price - discount) * product.quantity;
            });
          },
          finalPrices() {

            const products = { price: 0, discount: 0 };

            this.selectedProducts?.forEach(product => {
              products.price += (Number(product.price?.replace(/,/g, "") ?? 0) ?? 0) * product.quantity;
              products.discount += (Number(product.discount?.replace(/,/g, "") ?? 0) ?? 0) * product.quantity;
            });

            const shippingAmount = Number(this.shippingAmount?.toString().replace(/,/g, "") ?? 0);
            const discountOnOrder = Number(this.discountOnOrder?.toString().replace(/,/g, "") ?? 0);

            return {
              baseAmount: products.price,
              discountAmount: products.discount,
              discountOnOrder,
              shippingAmount,
              finalAmount: products.price - products.discount - discountOnOrder + shippingAmount,
            };

          },
        }
      }).mount('#app')

    </script>

  @endpush

  @push('styles')

    <link rel="stylesheet" href="{{ asset('assets/vue/multiselect/vue-multiselect.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vue/multiselect/custom-styles.css') }}" />

    <style>
      label,
      input {
        font-size: 12px !important;
      }
    </style>

  @endpush

</x-layouts.master>