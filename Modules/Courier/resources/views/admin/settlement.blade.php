<x-layouts.master title="تسویه با پیک" id="app">

  <div class="page-header">
    <x-breadcrumb>
      <x-breadcrumb-item title="تسویه با پیک" />
    </x-breadcrumb>
  </div>

  <x-card title="جستجوی پیشرفته">
    <x-form :action="route('admin.settlement.index')" method="GET">

      <x-row>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="انتخاب پیک" />
            <x-select name="courier_id" :data="$couriers" option-value="id" option-label="full_name" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="از تاریخ" />
            <x-date-input id="start_date" name="start_date" />
          </x-form-group>
        </x-col>

        <x-col lg="6" xl="3">
          <x-form-group>
            <x-label text="تا تاریخ" />
            <x-date-input id="end_date" name="end_date" />
          </x-form-group>
        </x-col>

      </x-row>

    </x-form>
  </x-card>

  <x-card title="سفارش ها">

    <x-table>
      <x-slot name="thead">
        <tr>
          <th>ردیف</th>
          <th>شناسه سفارش</th>
          <th>مشتری</th>
          <th>تاریخ ارسال</th>
          <th>تاریخ ثبت</th>
          <th>جمع آیتم ها</th>
          <th>هزینه ارسال</th>
          <th>تخفیف</th>
          <th>مبلغ نهایی</th>
          <th>از پور</th>
          <th>نقد</th>
          <th>کارت به کارت</th>
        </tr>
      </x-slot>
      <x-slot name="tbody">
        <tr v-for="(order, index) in orders">
          <td class="font-weight-bold">@{{ index + 1 }}</td>
          <td>
            <span class="badge badge-dark">@{{ order.id }}</span>
          </td>
          <td>
            <a :href="'/admin/customers/' + order.customer_id">
              @{{ order.customer.full_name + ' ' + order.customer.mobile }}
            </a>
          </td>
          <td>@{{ order.shamsi_created_at }}</td>
          <td>@{{ order.shamsi_delivered_at }}</td>
          <td>@{{ order.total_items_amount.toLocaleString() }}</td>
          <td>@{{ order.shipping_amount.toLocaleString() }}</td>
          <td>@{{ order.discount_amount.toLocaleString() }}</td>
          <td>@{{ order.total_amount.toLocaleString() }}</td>
          <td><input type="text" v-format-number class="form-control" @focus="setPozAmount(order.id)"
              v-model="order.posAmount" /></td>
          <td><input type="text" v-format-number class="form-control" @focus="setCashAmount(order.id)"
              v-model="order.cashAmount" /></td>
          <td><input type="text" v-format-number class="form-control" @focus="setCardByCardAmount(order.id)"
              v-model="order.cardByCardAmount" /></td>
        </tr>
        <tr>
          <td colspan="8">جمع کل</td>
          <td class="font-weight-bold fs-14">@{{ orders.reduce((sum, order) => sum + order.total_amount,
            0).toLocaleString() }}</td>
          <td class="font-weight-bold fs-14">@{{ sumAmouts.pos.toLocaleString() }}</td>
          <td class="font-weight-bold fs-14">@{{ sumAmouts.cash.toLocaleString() }}</td>
          <td class="font-weight-bold fs-14">@{{ sumAmouts.cardByCard.toLocaleString() }}</td>
        </tr>
      </x-slot>
    </x-table>

    <x-row v-if="orders.length" class="justify-content-center" style="gap: 8px">
      <button type="button" @click="store" class="btn btn-sm btn-primary"
        :disabled="isSubmitButtonDisabled">تسویه</button>
      <button type="button" @click="reset" class="btn btn-sm btn-red">ریست</button>
    </x-row>

  </x-card>

  @push('scripts')

    <script src="{{ asset('assets/vue/vue3/vue.global.prod.js') }}"></script>

    <script>
      Vue.createApp({
        data() {
          return {
            orders: @json($orders) ?? [],
            isSubmitButtonDisabled: false,
          }
        },
        methods: {
          removeComma(str) {
            return Number(str?.replace(/,/g, "") ?? 0);
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
          store() {

            const url = @json(route('admin.settlement.update'));
            const data = { orders: [] };

            this.orders.forEach(order => {
              data.orders.push({
                id: order.id,
                pos_amount: this.removeComma(order.posAmount),
                cash_amount: this.removeComma(order.cashAmount),
                card_by_card_amount: this.removeComma(order.cardByCardAmount),
              });
            });

            try {
              this.isSubmitButtonDisabled = true;
              this.request(url, 'PATCH', data, async (result) => {
                this.popup('success', '', result.message);
                this.reset();
              });
            } catch (error) {
              console.log(error);
            } finally {
              this.isSubmitButtonDisabled = false;
            }
          },
          reset() {
            window.location.reload();
          },
          setPozAmount(orderId) {
            const order = this.orders.find(o => o.id == orderId);
            if (!order.posAmount) {
              order.posAmount = order.total_amount.toLocaleString();
            }
          },
          setCashAmount(orderId) {
            const order = this.orders.find(o => o.id == orderId);
            if (!order.cashAmount) {
              order.cashAmount = order.total_amount.toLocaleString();
            }
          },
          setCardByCardAmount(orderId) {
            const order = this.orders.find(o => o.id == orderId);
            if (!order.cardByCardAmount) {
              order.cardByCardAmount = order.total_amount.toLocaleString();
            }
          },
        },
        computed: {
          sumAmouts() {

            const sumAmouts = { pos: 0, cash: 0, cardByCard: 0 };

            this.orders?.forEach(order => {
              sumAmouts.pos += this.removeComma(order.posAmount);
              sumAmouts.cash += this.removeComma(order.cashAmount);
              sumAmouts.cardByCard += this.removeComma(order.cardByCardAmount);
            });

            return sumAmouts;
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

    @stack('dateInputScriptStack')
    @stack('SelectComponentScripts')

  @endpush

  @push('styles')
    <style>
      input {
        font-size: 12px !important;
      }
    </style>
  @endpush

</x-layouts.master>