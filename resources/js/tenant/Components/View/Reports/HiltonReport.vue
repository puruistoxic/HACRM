<template>
  <div class="content-wrapper">
    <div class="">
      <app-page-top-section :title="$t('hilton_sales_report')" icon="briefcase">
      </app-page-top-section>
    </div>
    <iframe
      src="https://app.powerbi.com/view?r=eyJrIjoiZDYzNTc4YjItMWExNy00NzliLWEzMTItZWFmNGU0NWY2ODE2IiwidCI6ImQ5NGIzM2Q1LWY0NDYtNGFjYS1hOWVjLTE4OWE3NGIwNTE1NyJ9"
      frameborder="0"
      width="100%"
      height="100%"
    ></iframe>
  </div>
</template>
