# Changelog

<a name="1.0.1"></a>
## 1.0.1 (2023-10-30)

### Added

- ✅ Adicionando migration e seeders aos testes. [[ddf336f](https://github.com/GustavoHBO/EventManagementSystem/commit/ddf336f0c286ef534236fbd66a51e8fe583bafd0)]
- ✨ Adicionando possibilidade de adicionar ou remover papéis ou permissões a usuário. [[3dde646](https://github.com/GustavoHBO/EventManagementSystem/commit/3dde646cfd10882b54d14e2c8f39a185f135edea)]
- ✨ Removendo automaticamente pagamentos expirados. [[c3c2314](https://github.com/GustavoHBO/EventManagementSystem/commit/c3c23143846d2d25770052ea97636936f9b2ecb7)]
- ✨ Adicionando notificações para os usuários. [[dc9dde4](https://github.com/GustavoHBO/EventManagementSystem/commit/dc9dde4d92b915650c7974c1f4126dbc9e9481c0)]
- ✨ Adicionando comando para atualizar pagamentos não realizados dentro do prazo de pagamento. [[4be25b4](https://github.com/GustavoHBO/EventManagementSystem/commit/4be25b49eccdbbcf3b9aaaad42128f6a232817f0)]
- ➕ Adicionando biblioteca de geração de QrCode [[020c87f](https://github.com/GustavoHBO/EventManagementSystem/commit/020c87f1f042490c5dd65a1a09bc4999594e7902)]
- ✨ Adicionando novas rotas. [[ca8d926](https://github.com/GustavoHBO/EventManagementSystem/commit/ca8d926bbffe1a210bad2b805a8f3df144d576e7)]
- ✨ Paginação nos eventos [[a905782](https://github.com/GustavoHBO/EventManagementSystem/commit/a905782f34342f142c21827efdba7e03eecb867c)]

### Changed

- 🚚 Adicionando rotas novas e removendo rotas não utilizadas. [[a826014](https://github.com/GustavoHBO/EventManagementSystem/commit/a82601447dfe5624ae2466e5be12cbbf0b3eed9c)]
- ♻️ Removendo relacionamentos não utilizados e adicionando documentação. [[2276d3d](https://github.com/GustavoHBO/EventManagementSystem/commit/2276d3d62f447987ad8527af32257c0c0a65d312)]
- ♻️ Refatoração de código, removendo funções não utilizadas e adicionando documentação ao código. [[46aa0e7](https://github.com/GustavoHBO/EventManagementSystem/commit/46aa0e7370da3613f45098a2dd52894fcb4fff71)]
- 🔧 Adicionando configuração para lidar com os webhooks da aplicação. [[b3e5eb2](https://github.com/GustavoHBO/EventManagementSystem/commit/b3e5eb2f06f81dd59ef201e54b79a273e60e8f58)]
- 🚚 Adicionando rotas paras os novos controllers. [[c50f1b0](https://github.com/GustavoHBO/EventManagementSystem/commit/c50f1b049c5dc5411284aa510a1ac344ca718506)]
- 🚸 Layout de emails [[a39e17e](https://github.com/GustavoHBO/EventManagementSystem/commit/a39e17e8535f88b83613f625024cc5c40a71bbb2)]
- 🚸 Adicionando notificação para adição de usuários a um time. [[d514dc0](https://github.com/GustavoHBO/EventManagementSystem/commit/d514dc0c5f3e00bcef8c858210a77bb9e96cc18c)]
- ♻️ Encurtando a importação da biblioteca de email. [[e4a168d](https://github.com/GustavoHBO/EventManagementSystem/commit/e4a168dbe4d73b9432ed8901a68c07716e79fee5)]
- 🚸 Adicionando mensagens de notificações para usuários. [[c14e3c3](https://github.com/GustavoHBO/EventManagementSystem/commit/c14e3c361ec8d9d2bae224a1cd5af3f2a53c79b1)]
- 🗃️ Melhorando performance para pagamentos, adicionando seeders necessários e factory para pagamentos. [[b33ecd2](https://github.com/GustavoHBO/EventManagementSystem/commit/b33ecd2e17d1ddcd0f1e2e3c4db4456042be723f)]
- ♻️ Refatorando parte do código, ajustando relacionamentos. [[f792533](https://github.com/GustavoHBO/EventManagementSystem/commit/f79253365c1b742de3daa6c88c34f30dfed7f3fe)]
- 🗃️ Ajustes no relacionamento. [[9fa9498](https://github.com/GustavoHBO/EventManagementSystem/commit/9fa9498fb4bf0df19935c038cb3efe7a437c85ea)]
- ♻️ Ajustes no código para adicionar relacionamentos e ajustar os relacionamentos afetados pelas modificações na migrations. [[14e0bf5](https://github.com/GustavoHBO/EventManagementSystem/commit/14e0bf55b2d1d9ae7aeb27858f0e7a44e37f41c0)]
- 🗃️ Atualizando migrations para melhorar relacionamento entre os pedidos e os ingressos. [[e8d95c2](https://github.com/GustavoHBO/EventManagementSystem/commit/e8d95c25f206239be5de927dd509dde1ddfd1120)]
- ♻️ Modificando as permissões [[543db2b](https://github.com/GustavoHBO/EventManagementSystem/commit/543db2b15e2c47d0f705f33675c9b424e128f1c4)]
- ♻️ Resources dos modelos [[53e8dc5](https://github.com/GustavoHBO/EventManagementSystem/commit/53e8dc5695efa80d110ae2ed0bff56ad4155437f)]
- 🗃️ Modificação na estrutura da tabela de ingressos. [[6a78444](https://github.com/GustavoHBO/EventManagementSystem/commit/6a7844413ccf09ddb55a79e326d563846195fc4e)]
- 🔧 Atualizando configurações para o projeto. [[1616a45](https://github.com/GustavoHBO/EventManagementSystem/commit/1616a450eece6383a709867e5d22db522163ef37)]
- 🔧 Atualizando configuração. Adicionando middlewares e ajustando permissões. [[262164a](https://github.com/GustavoHBO/EventManagementSystem/commit/262164abdfb57d2a37935ce41995055bc28f37a9)]
- 🚚 Resources [[042ab81](https://github.com/GustavoHBO/EventManagementSystem/commit/042ab815c673c52e9af46c55979ec545bb22453c)]
- 🚸 Middleware para os times e para a autenticação. [[c9ef7e5](https://github.com/GustavoHBO/EventManagementSystem/commit/c9ef7e5907b755ed8f3603834019074762460f11)]
- 🗃️ Modificação na estrutura para permitir associar evento a times [[6914120](https://github.com/GustavoHBO/EventManagementSystem/commit/69141208cd460b3d87f6dd5e5d560e9ab16fbf9b)]

### Removed

- 🔥 Removendo arquivos não mais necessários. [[2129bc7](https://github.com/GustavoHBO/EventManagementSystem/commit/2129bc72eaf11b9fd96e085666d123ae8029789b)]

### Security

- 🔒 Adicionando permissão [[73cf68d](https://github.com/GustavoHBO/EventManagementSystem/commit/73cf68dd123ad35e185d3b08064ee4eeebadbdc5)]
- 🔒 Encriptando dados [[e28b5e2](https://github.com/GustavoHBO/EventManagementSystem/commit/e28b5e266148ebd4824bd9673f79f116ecc9d6f9)]

### Miscellaneous

-  Merge branch &#x27;feature/gu/updates&#x27; into develop [[0c0d25d](https://github.com/GustavoHBO/EventManagementSystem/commit/0c0d25d11920801bc1f2db8242db43f87ad4b922)]
- 📝 Atualizando README. [[5d4e418](https://github.com/GustavoHBO/EventManagementSystem/commit/5d4e418e434f1aaaf50ac96273502d006dbbda9c)]
-  Merge branch &#x27;feature/gu/ConfigProj&#x27; into develop [[d2b1782](https://github.com/GustavoHBO/EventManagementSystem/commit/d2b17826683876c92db5bf0d53bc435df9cf2d25)]
- 🧑‍💻 Variáveis de testes e credenciais de homologação. [[14b7dda](https://github.com/GustavoHBO/EventManagementSystem/commit/14b7dda154b8fe9ad76fde4154ed2ebd34ab37e9)]
- 🤡 Factories para os modelos. [[4b7eed4](https://github.com/GustavoHBO/EventManagementSystem/commit/4b7eed44122e0cf2e34287fe048eb526b111eac5)]
- 🧪 Adicionando testes de unidade e de funcionalidade. [[5ccda16](https://github.com/GustavoHBO/EventManagementSystem/commit/5ccda163869e819ca3b379f6f9763ef355d8e5a7)]
- 🌱 Atualizando seeders base [[4b95cc0](https://github.com/GustavoHBO/EventManagementSystem/commit/4b95cc019fdde7266701b76cfa780046d2f90efe)]
- 👔 Atualizando a lógica de negócio para o novo modelo de banco e ajustes no webhook. [[2407f37](https://github.com/GustavoHBO/EventManagementSystem/commit/2407f37cc402a6ec05644c8b5b4ea2a57af24ebe)]
- 👔 Atualizando lógica de negócio para os times [[ef2d7d7](https://github.com/GustavoHBO/EventManagementSystem/commit/ef2d7d7be3ea66a4b9e36368afa27d2c6464badc)]
- 🚧 Atualizando models [[f8f1b1e](https://github.com/GustavoHBO/EventManagementSystem/commit/f8f1b1e84abfb74e735f73921f03e7dd6f9d58f8)]
- 🛂 Adicionando novas permissões [[faed503](https://github.com/GustavoHBO/EventManagementSystem/commit/faed5033a90ffcfcd83a02f81fd47790203a7736)]
- 🚧 Adicionando interface para métodos de pagamento. [[281d7b6](https://github.com/GustavoHBO/EventManagementSystem/commit/281d7b6f096cab7f78d03e22a15cad6f66b4d8fe)]
- 🚧 Ajustando jobs [[555914d](https://github.com/GustavoHBO/EventManagementSystem/commit/555914db57e71a2a6086fb072286c10ff1cc70ce)]
- 🧑‍💻 Atualizando .env.example [[432c503](https://github.com/GustavoHBO/EventManagementSystem/commit/432c50317e73a0c38b619413b64c9cbc092b9a50)]
- 🚧 Modificando lógicas de negócio [[735c4db](https://github.com/GustavoHBO/EventManagementSystem/commit/735c4db79fa3ddfe3fbbddccff9e9602a9355e6d)]
- 📝 Documentação da estrutura do banco de dados. [[88f3a2e](https://github.com/GustavoHBO/EventManagementSystem/commit/88f3a2e583a33bbacdbc08049911690162d07b81)]
- 🧐 Resources para padronização das respostas. [[299580e](https://github.com/GustavoHBO/EventManagementSystem/commit/299580e9e0ffab7b9cf102c7dc04cb1ba1b92f38)]
- 🌱 Adicionando novos seeders importantes para o projeto. [[4f24dd6](https://github.com/GustavoHBO/EventManagementSystem/commit/4f24dd6831d72c1ea33453934e27e10d5145ee88)]
- 🚧 Criação do controller de pedidos e ajustes no controller de eventos para contabilizar a quantida de ingressos e modificar estrutura do relacionamento dos itens. [[871b45a](https://github.com/GustavoHBO/EventManagementSystem/commit/871b45a8fdfc5c2089f2d1e51dac0f37cf80e9b1)]
- 🚧 Ajustes na lógica da estrutura de negócio [[5a78e69](https://github.com/GustavoHBO/EventManagementSystem/commit/5a78e69f913ad956e5502fd6112d4923d06efe12)]
- 🚧 Métodos de relacionamento entre modelos [[4b51c98](https://github.com/GustavoHBO/EventManagementSystem/commit/4b51c98627b49101da45f840cc93cb360e03c168)]
- 🚧 Adicionando métodos de relacionamentos entre modelos [[858fff2](https://github.com/GustavoHBO/EventManagementSystem/commit/858fff2afff51167aaea6fb965f65321d4ad09ad)]
- 🚧 Controllers da aplicação [[6a1e28d](https://github.com/GustavoHBO/EventManagementSystem/commit/6a1e28d561afb1531a214f4c0952cf83f9796f8c)]
- 🌱 Atualizando seeder [[19f2b6f](https://github.com/GustavoHBO/EventManagementSystem/commit/19f2b6f3bb504df9649eaf49f1f2795676195edf)]
- 👔 Buscando o time do usuário a partir dos dados criados no login. [[8e2be08](https://github.com/GustavoHBO/EventManagementSystem/commit/8e2be086ba3268f1e9b3edfc095953c609682403)]
- 🚧 Models da aplicação [[36bc081](https://github.com/GustavoHBO/EventManagementSystem/commit/36bc081cf7e392f54da5cb0b61c53ce852bb2005)]
- 🥅 Exception para captura de erros. [[62053f1](https://github.com/GustavoHBO/EventManagementSystem/commit/62053f18a83c5ef4942ea4b58db2b2eee407b822)]
- 🚧 Contrução dos business [[0800158](https://github.com/GustavoHBO/EventManagementSystem/commit/0800158f121e5c5eab429e186daecd48a681326f)]
- ⚰️ Removendo seeders desnecessários [[605d3fb](https://github.com/GustavoHBO/EventManagementSystem/commit/605d3fb670d626d3dc1b033f64cfaf60a27b882c)]
- 🧑‍💻 Ajustando .env de exemplo [[d0cb5b9](https://github.com/GustavoHBO/EventManagementSystem/commit/d0cb5b99417e54f1ef0fd73c61fcca5bc608d69c)]
- 🚧 Ajustes nas rotas [[9b4ab0c](https://github.com/GustavoHBO/EventManagementSystem/commit/9b4ab0c2630168fe0d52f7232737c085967ee79a)]
-  👷 Ajustando composer json para o ambiente de devesenvolivemto e produção. [[df84afa](https://github.com/GustavoHBO/EventManagementSystem/commit/df84afaa617308adfff9164dad6262a64aac537c)]
- 👔 Modelos de business [[b43e37a](https://github.com/GustavoHBO/EventManagementSystem/commit/b43e37ac7adf2bf29206de8aad9657707f1c6fa6)]
- 🌱 Seeders para o projeto [[cbfa59f](https://github.com/GustavoHBO/EventManagementSystem/commit/cbfa59f7e771b3cc82c525a9109369924f8921fe)]
- 🛂 Controllers da aplicação [[150c5b6](https://github.com/GustavoHBO/EventManagementSystem/commit/150c5b61efb5a8dc8652682acad71d06520eddc8)]
- 🚧 Models do projeto [[72caf89](https://github.com/GustavoHBO/EventManagementSystem/commit/72caf89967327c9ecd9e2e5377c89a6bcd4b51a0)]
- 🚧 Modelos para as tabelas criadas [[9416745](https://github.com/GustavoHBO/EventManagementSystem/commit/9416745a4914bfe5c5f279287852a1686593c212)]
- 🚧 Adicionando as migrations para o banco de dados [[18ded28](https://github.com/GustavoHBO/EventManagementSystem/commit/18ded28618ac0c72b27dd79ac27048859343713b)]
- 🚧 Atualizando projeto com as configurações iniciais. [[9abbf9e](https://github.com/GustavoHBO/EventManagementSystem/commit/9abbf9e9c03d1bbb118923dbc6e97adc5d98abf6)]
-  Initial commit [[385b36f](https://github.com/GustavoHBO/EventManagementSystem/commit/385b36f33a67fddeb6071369504f20e3772674c8)]


