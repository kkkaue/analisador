<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

import { Loader2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button'
import {
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form'
import { Textarea } from '@/components/ui/textarea'
import { Progress } from '@/components/ui/progress'
import { ref } from 'vue';

defineProps({
    resultado: {
        type: String,
    },
});

let isLoading = ref(false);
let progressValue = ref(0);
let resultado = ref(null);

const form = useForm({
    codigo: null,
});

const startLoading = () => {
    isLoading.value = true;
    progressValue.value = 0;
    resultado.value = null;
    const interval = setInterval(() => {
        progressValue.value += 1;
        if (progressValue.value >= 100) {
            clearInterval(interval);
        }
    }, 10);
};

const submit = () => {
    startLoading();
    form.post(route('analisador.analisar'), {
        preserveScroll: true,
        onSuccess: (page) => {
            const checkProgress = setInterval(() => {
                if (progressValue.value >= 100) {
                    clearInterval(checkProgress);
                    setTimeout(() => {
                        resultado.value = page.props.resultado;
                        isLoading.value = false;
                    }, 1000);
                }
            }, 10);
        },
    });
};
</script>

<template>
    <Head title="Analisador" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-center text-xl text-gray-800 leading-tight dark:text-gray-200">
                Analisador Léxico
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-200">
                        <form class="w-full space-y-6" @submit.prevent="submit">
                            <FormField v-slot="{ componentField }" name="codigo">
                            <FormItem>
                                <FormLabel>Digite seu código</FormLabel>
                                <FormControl>
                                <Textarea
                                    placeholder="int a = 10;"
                                    class="resize-none"
                                    v-bind="componentField"
                                    v-model="form.codigo"
                                />
                                </FormControl>
                                <FormDescription>
                                    Digite seu código para ser analisado
                                </FormDescription>
                                <FormMessage />
                            </FormItem>
                            </FormField>
                            <Button :disabled="isLoading">
                                <span v-if="!isLoading">
                                    Analisar
                                </span>
                                <span v-else class="flex">
                                    <Loader2 class="w-4 h-4 mr-2 animate-spin" />
                                    Analisando
                                </span>
                            </Button>
                        </form>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800" v-if="isLoading || resultado">
                    <div class="p-6 text-gray-900 dark:text-gray-200">
                        <Progress :model-value="progressValue" v-if="!resultado" />
                        <div v-else v-html="resultado" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
