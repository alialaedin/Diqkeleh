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
        <div v-if="showCancleCustomerButton" class="d-flex mb-4" style="gap: 8px">
          <button class="btn btn-sm btn-danger" @click="removeCustomer">لغو</button>
          <span class="badge badge-info">کیف پول : @{{ customer.wallet.balance.toLocaleString() }} تومان</span>
        </div>
      </div>

      <template v-if="customer">

        <div class="col-12 col-xl-3">
          <fieldset class="form-group">
            <label>نام و نام خانوادگی <span class="text-danger">&starf;</span></label>
            <input type="text" class="form-control" v-model="customer.full_name" required />
          </fieldset>
        </div>

        <div v-if="!isOrderInPerson" class="col-12 col-xl-3">
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

    <div class="row">
      <div class="col-12">
        <fieldset class="form-group">
          <label class="custom-switch">
            <input type="checkbox" class="custom-switch-input" v-model="isOrderInPerson">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description">تحویل حضوری</span>
          </label>
        </fieldset>
      </div>
    </div>

  </x-card>

  <div class="row d-print-none">

    <div class="col-md-12 col-xl-3">
      <div class="card">
        <div class="nav flex-column admisetting-tabs" id="settings-tab" role="tablist" aria-orientation="vertical">
          <template v-for="(category, index) in categories">
            <a :class="{ 'nav-link': true, 'active': index == 0 }" data-toggle="pill" :href="'#tab-' + index" role="tab"
              aria-selected="false">
              @{{ category.title }}
            </a>
          </template>
        </div>
      </div>
    </div>

    <div class="col-md-12 col-xl-9">
      <div class="tab-content adminsetting-content" id="setting-tabContent">
        <template v-for="(category, index) in categories">
          <div :class="{ 'tab-pane fade': true, 'show active': index == 0 }" :id="'tab-' + index" role="tabpanel">
            <div class="card">
              <div class="card-body">
                <template v-for="(product, productIndex) in getProductsByCategoryId(category.id)">
                  <div class="form-group">
                    <div class="row align-items-center">
                      <div class="col-md-3">
                        <span class="fs-14 font-weight-bold">@{{ product.title }}</span>
                      </div>
                      <div class="col-md-9 d-flex align-items-center" style="gap: 8px">

                        <button class="btn btn-sm btn-outline-danger" @click="decreaseCartQuantity(product)"
                          :disabled="product.quantity == 0">کاهش</button>

                        <span>@{{ product.quantity }}</span>

                        <button class="btn btn-sm btn-outline-primary" @click="increaseCartQuantity(product)"
                          :disabled="product.quantity == product.store.balance">افزایش</button>

                        {{-- <button type="button" @click="decreaseCartQuantity(product)"
                          :disabled="product.quantity == 0" class="px-1 bg-none"
                          style="border: 1px solid #e9ebfa !important;vertical-align: top">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16" class="bi bi-dash">
                            <path data-v-59b5239a="" d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z">
                            </path>
                          </svg>
                        </button>
                        <span class="px-2">@{{ product.quantity }}</span>
                        <button type="button" @click="increaseCartQuantity(product)"
                          :disabled="product.quantity == product.store.balance" class="px-1 bg-none"
                          style="border: 1px solid #e9ebfa !important;vertical-align: top">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16" class="bi bi-plus">
                            <path data-v-5558c195=""
                              d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z">
                            </path>
                          </svg>
                        </button> --}}

                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>

  </div>

  <x-card v-if="selectedProducts.length" title="محصولات انتخاب شده" class="d-print-none">
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
        </tr>
      </x-slot>
      <x-slot name="tbody">
        <tr v-for="(product, index) in selectedProducts" :key="index">
          <td class="font-weight-bold">@{{ index + 1 }}</td>
          <td>@{{ product.category.title }}</td>
          <td>@{{ product.title }}</td>
          <td>
            <input type="text" v-model="product.price" class="form-control text-center" v-format-number />
          </td>
          <td>
            <input type="text" v-model="product.discount" class="form-control text-center" v-format-number />
          </td>
          <td>@{{ product.store.balance }}</td>
          <td>
            <button type="button" @click="decreaseCartQuantity(product)" :disabled="product.quantity == 0"
              class="px-1 bg-none" style="border: 1px solid #e9ebfa !important;vertical-align: top">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                class="bi bi-dash">
                <path data-v-59b5239a="" d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"></path>
              </svg>
            </button>
            <span class="px-2">@{{ product.quantity }}</span>
            <button type="button" @click="increaseCartQuantity(product)"
              :disabled="product.quantity == product.store.balance" class="px-1 bg-none"
              style="border: 1px solid #e9ebfa !important;vertical-align: top">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                class="bi bi-plus">
                <path data-v-5558c195=""
                  d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z">
                </path>
              </svg>
            </button>
          </td>
          <td>@{{ productFinalPrices[index].toLocaleString() }}</td>
        </tr>
      </x-slot>
    </x-table>
  </x-card>

  <x-card title="اطلاعات سفارش" class="d-print-none">

    <x-row>

      <x-col xl="3">
        <x-form-group>
          <x-label text="تخفیف روی سفارش (تومان)" />
          <x-input v-model="discountOnOrder" type="text" name="discountOnOrder" v-format-number />
        </x-form-group>
      </x-col>

      <x-col xl="3">
        <x-form-group>
          <x-label text="هزینه ارسال (تومان)" />
          <x-input v-model="shippingAmount" type="text" name="shippingAmount" v-format-number />
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
            <b class="fs-12">مبلغ کارت به کارت (تومان) :</b>
            <input v-model="cardByCardAmount" type="text" class="text-left form-control" style="width: auto"
              v-format-number />
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">مبلغ پرداخت نقدی (تومان) :</b>
            <input v-model="cashAmount" type="text" class="text-left form-control" style="width: auto"
              v-format-number />
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">پرداخت از پوز (تومان) :</b>
            <input v-model="posAmount" type="text" class="text-left form-control" style="width: auto" v-format-number
              @focus="setPosAmount" />
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">پرداخت از کیف پول (تومان) :</b>
            <input v-model="fromWalletAmount" type="text" class="text-left form-control" style="width: auto"
              v-format-number />
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <b class="fs-12">مبلغ قابل پرداخت :</b>
            <span class="fs-12 text-primary fs-15">@{{ finalPrices.finalAmount.toLocaleString() }} تومان</span>
          </div>

        </div>
      </x-card>
    </x-col>

    <x-col class="text-center">
      <button type="button" @click="store" class="btn btn-sm btn-success mx-2" :disabled="isStoreButtonDisabled">ثبت
        سفارش</button>
      <button type="button" @click="print" class="btn btn-sm btn-purple mx-2">پرینت</button>
      <button type="button" @click="reset" class="btn btn-sm btn-red mx-2">ریست</button>
    </x-col>

    <div style="margin-top: 80px"></div>

  </x-row>

  <section class="section-print my-5 d-none d-print-block">
    <h2 class=" mb-4">شماره سفارش: @{{ createdOrder?.id }}</h2>
    <div class="d-flex flex-column">
      <div clas="d-flex align-items-center">
        <span class="fs-13">تاریخ: </span>
        <time class="font-weight-bold fs-14">@{{ createdOrder?.shamsi_created_at }}</time>
      </div>
      <div clas="d-flex align-items-center">
        <span class="fs-13">شماره فاکتور: </span>
        <span class="font-weight-bold fs-14">@{{ createdOrder?.id }}</span>
      </div>
      <div clas="d-flex align-items-center">
        <span class="fs-13">موبایل مشتری: </span>
        <span class="font-weight-bold fs-14">@{{ customerMobile }}</span>
      </div>
    </div>
    <h3 class="section-title text-center p-1 mt-4">اقلام سفارش</h3>
    <table class="print-table table d-table w-100">
      <thead>
        <tr>
          <th>ردیف</th>
          <th>عنوان</th>
          <th>تعداد</th>
          <th>قیمت</th>
          <th>تخفیف</th>
          <th>جمع کل</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(product, index) in selectedProducts" :key="index">
          <td>@{{ index + 1 }}</td>
          <td>@{{ product.title }}</td>
          <td>@{{ product.quantity }}</td>
          <td>@{{ Number(product.price?.replace(/,/g, "") ?? 0).toLocaleString() }}</td>
          <td>@{{ Number(product.discount?.replace(/,/g, "") ?? 0).toLocaleString() }}</td>
          <td>@{{ productFinalPrices[index].toLocaleString() }}</td>
        </tr>
      </tbody>
    </table>
    <table v-if="selectedProducts.length" class="print-table table d-table w-100">
      <tbody>
        <tr>
          <td>تعداد کالا</td>
          <td>@{{ selectedProducts.reduce((sum, product) => sum + product.quantity, 0) }}</td>
        </tr>
        <tr>
          <td>مجموع قیمت کالا (تومان)</td>
          <td>@{{ finalPrices.baseAmount.toLocaleString() }}</td>
        </tr>
        <tr>
          <td>تخفیف (تومان)</td>
          <td>@{{ finalPrices.discountAmount.toLocaleString() }}</td>
        </tr>
        <tr>
          <td>از پوز (تومان)</td>
          <td>@{{ finalPrices.finalAmount.toLocaleString() }}</td>
        </tr>
      </tbody>
    </table>
    <div v-if="!isOrderInPerson" class="d-flex flex-column" style="gap: 4px">
      <div clas="d-flex align-items-center">
        <span class="fs-13">محدوده: </span>
        <time class="font-weight-bold fs-14">@{{ address?.range.title }}</span>
      </div>
      <div clas="d-flex align-items-center">
        <span class="fs-13">آدرس: </span>
        <span class="font-weight-bold fs-14">@{{ address?.address }}</span>
      </div>
    </div>
    <div v-if="createdOrder?.description" class="mt-3">@{{ createdOrder?.description }}</div>
  </section>

  <x-modal id="new-address-modal" title="ثبت آدرس جدید">
    <x-row>
      <x-col>
        <x-form-group>
          <x-label :is-required="true" text="شماره همراه" />
          <x-input type="text" name="mobile" v-model="newAddress.mobile" />
        </x-form-group>
      </x-col>
      <x-col>
        <x-form-group>
          <x-label :is-required="true" text="انتخاب محدوده" />
          <multiselect v-model="newAddress.range" :options="ranges" placeholder="انتخاب محدوده" track-by="id"
            :show-labels="false" label="title" class="custom-multiselect" />
        </x-form-group>
      </x-col>
      <x-col>
        <x-form-group>
          <x-label :is-required="true" text="آدرس" />
          <x-textarea rows="3" name="address" v-model="newAddress.address" />
        </x-form-group> </x-col>
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

      const closeModal = (selector) => $(selector).modal('hide');

      Vue.createApp({
        components: {
          'multiselect': window['vue-multiselect'].default,
        },
        mounted() {
          this.products = this.products.map(p => ({
            ...p,
            quantity: 0,
            price: p.unit_price.toLocaleString(),
            discount: p.discount_amount.toLocaleString(),
          }));
        },
        data() {
          return {
            customerSearchUrl: @json(route('admin.customers.order-search')),
            createAddressUrl: @json(route('admin.addresses.store')),
            customerMobile: '',
            customer: null,
            addresses: [],
            address: null,
            discountOnOrder: null,
            description: '',
            showCancleCustomerButton: false,
            isStoreButtonDisabled: false,
            abortController: null,
            debounceTimeout: null,
            ranges: @json($ranges),
            categories: @json($categories),
            category: null,
            products: @json($products),
            shippingAmount: 0,
            cardByCardAmount: null,
            cashAmount: null,
            posAmount: null,
            fromWalletAmount: 0,
            isOrderInPerson: false,
            createdOrder: null,
            newAddress: {
              mobile: null,
              address: null,
              customer_id: null,
              range_id: null,
              range: null
            }
          }
        },
        methods: {
          formatNumber(event) {

            if (event.which >= 37 && event.which <= 40) return;

            let input = event.target;
            let value = input.value.replace(/[^\d]/g, '');

            if (value === '') {
              input.value = '';
              return;
            }

            let numberValue = parseInt(value, 10);

            if (isNaN(numberValue)) {
              input.value = '';
              return;
            }

            input.value = numberValue.toLocaleString('en-US');
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

            if (!/^[0-9]+$/.test(this.customerMobile)) {
              this.popup('warning', 'خطای اعتبار سنجی', 'شماره همراه باید فقط عدد باشد');
              this.customerMobile = '';
              return;
            }

            if (!this.customerMobile.startsWith("09")) {
              this.popup('warning', 'خطای اعتبار سنجی', 'شماره همراه باید با 09 شروع شود');
              this.customerMobile = '';
              return;
            }

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

            this.newAddress.mobile = null;
            this.newAddress.address = null;
            this.newAddress.customer_id = null;
            this.newAddress.range_id = null;

          },

          productCustomLabel({ id, title, store }) {
            return `${title} | موجودی: ${store.balance}`;
          },
          removeProduct(product) {
            product.quantity = 0;
          },
          getProductsByCategoryId(categoryId) {
            return this.products.filter(p => p.category_id == categoryId) ?? [];
          },
          increaseCartQuantity(product) {
            if (product.quantity < product.store.balance) {
              product.quantity = product.quantity + 1;
            }
          },
          decreaseCartQuantity(product) {
            if (product.quantity >= 1) {
              product.quantity = product.quantity - 1;
            }
          },

          setPosAmount() {
            if (!this.posAmount) {

              const finalAmount = this.finalPrices.finalAmount;
              const fromWalletAmount = Number(this.fromWalletAmount?.toString().replace(/,/g, "") ?? 0);

              this.posAmount = (finalAmount - fromWalletAmount).toLocaleString();
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
          reset() {
            window.location.reload();
          },
          store() {

            const fromWalletAmount = Number(this.fromWalletAmount?.toString().replace(/,/g, "") ?? 0);

            if (this.customer == null) {
              this.popup('warning', 'خطای اعتبار سنجی', 'ابتدا مشتری را انتخاب کنید');
              return;
            }

            if (fromWalletAmount > 0 && fromWalletAmount > this.customer.wallet.balance) {
              this.popup('warning', 'خطای اعتبار سنجی', 'میزان پرداختی از کیف پول بیشتر از موجودی کاربر است');
              return;
            }

            if (this.customer.full_name?.trim().length == 0) {
              this.popup('warning', 'خطای اعتبار سنجی', 'نام مشتری وارد نشده است');
              return;
            }

            if (!this.isOrderInPerson && this.address == null) {
              this.popup('warning', 'خطای اعتبار سنجی', 'ابتدا یک آدرس انتخاب کنید');
              return;
            }

            this.isStoreButtonDisabled = true;

            const url = @json(route('admin.orders.store'));
            const data = {
              customer_id: this.customer.id,
              address_id: this.address?.id ?? null,
              is_in_person: this.isOrderInPerson,
              from_wallet_amount: fromWalletAmount,
              description: this.description ?? null,
              full_name: this.customer.full_name,
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
                this.createdOrder = result.data.order;
                setTimeout(() => this.print(), 100);
                // this.popup('success', '', result.message);
              });
            } catch (error) {
              console.log(error);
            } finally {
              this.isStoreButtonDisabled = false;
            }

          },
        },
        watch: {
          address(value) {
            if (value) {
              this.shippingAmount = value?.range.shipping_amount.toLocaleString() ?? 0;
            }
          },
          isOrderInPerson(newVal) {
            console.log(newVal);
          },
          isCustomerMobileInputDisabled(disabled) {
            if (disabled) {
              this.searchCustomer();
            }
          },
          customer(customer) {
            this.addresses = customer == null ? [] : customer.addresses;
            if (customer) {
              this.newAddress.mobile = customer.mobile;
              this.newAddress.customer_id = customer.id;
              this.fromWalletAmount = customer.wallet.balance.toLocaleString();
            } else {
              this.fromWalletAmount = null;
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
            this.product = null;
          },
          'newAddress.range'(range) {
            if (range != null) {
              this.newAddress.range_id = range.id;
            }
          },
        },
        computed: {
          selectedProducts() {
            return this.products.filter(p => p.quantity > 0) ?? [];
          },
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
      }).directive('format-number', {
        beforeMount(el) {
          const format = (e) => {
            let rawValue = el.value.replace(/[^\d]/g, '');

            if (!rawValue) {
              if (el.value !== '') {
                el.value = '';
                el.dispatchEvent(new Event('input', { bubbles: true }));
              }
              return;
            }

            let formatted = Number(rawValue).toLocaleString('en-US');

            if (el.value !== formatted) {
              let position = el.selectionStart;
              let oldLength = el.value.length;
              el.value = formatted;
              let newLength = el.value.length;
              position += newLength - oldLength;

              requestAnimationFrame(() => {
                el.setSelectionRange(position, position);
              });

              el.dispatchEvent(new Event('input', { bubbles: true }));
            }
          };

          el.addEventListener('input', format);
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

    <style>
      .section-print {
        padding: 70px;
      }

      @media (max-width:1100px) {
        .section-print {
          padding: 50px;
        }
      }

      @media (max-width:680px) {
        .section-print {
          padding: 25px;
        }
      }

      .print-table {
        display: table;
      }

      .print-table td {
        border: 1px solid #cdcbcb;
        padding: 5px 10px;
        vertical-align: top;
        font-size: 14px;
        text-align: center;
      }

      .print-table th {
        border: 1px solid #cdcbcb;
        background: #f0f0f0;
        font-weight: bold;
        text-align: right;
        padding: 5px 10px;
        text-align: center;
      }

      .btn-print {
        background-color: rgb(0, 102, 255);
        color: white;
        border-radius: 10px;
      }

      .section-title {
        background-color: gray;
        color: white;
        margin-bottom: 10px;
      }
    </style>

  @endpush

</x-layouts.master>