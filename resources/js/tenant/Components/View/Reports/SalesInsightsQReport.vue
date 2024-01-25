<template>
  <div class="content-wrapper">
    <div class="">
      <app-page-top-section
        :title="$t('sales_insights_q_hotel')"
        icon="briefcase"
      >
      </app-page-top-section>
    </div>
    <iframe
      src="https://app.powerbi.com/view?r=eyJrIjoiNWUzM2Y4MjMtODkzNC00MjEyLTllMjAtNTFhZjkwMzEyNWQ3IiwidCI6ImQ5NGIzM2Q1LWY0NDYtNGFjYS1hOWVjLTE4OWE3NGIwNTE1NyJ9"
      frameborder="0"
      width="100%"
      height="100%"
    ></iframe>
  </div>
</template>
