import { axiosGet } from "../../common/Helper/AxiosHelper";
import { CONTACTPERSON } from "../../tenant/Config/ApiUrl-CPB";
const state = {
    personlist: []
};

const getters = {
    getPerson: state => state.personlist
};

const actions = {
    getPerson({ commit }, customerId) {
        axiosGet(`${CONTACTPERSON}/${customerId}`).then(({ data }) => {
            commit('PERSON_INFO', data)
        }).catch((error) => this.$toastr.e(error));
    }
};

const mutations = {
    PERSON_INFO(state, data) {
        state.personlist = data;
    }
};


export default {
    state,
    getters,
    actions,
    mutations
}
