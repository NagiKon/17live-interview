# PHP 當中的 interface 和 abstract ，分別適合於什麼時機使用。請描述對於這兩個保留字的看法。

## abstract 與 interface 的差異

abstract 與 interface 都是被用來實現「抽象」的關鍵字，可以從下方的對照表看出他們使用時不同的地方。

**對照表：**

| Abstract                                                    | Interface                                  |
| :---------------------------------------------------------- | ------------------------------------------ |
| 用於 class 上，由 class 直接繼承。                          | 獨立於 class，由 class implement。         |
| 一個 class 只能繼承一次。                                   | 一個 class 可以 implement 多個 interface。 |
| 一個 abstract class 只能繼承一個 abstract class。           | 一個 interface 可以繼承多個 interface。    |
| 需要被非 abstract class 的 class 所繼承，否則無法被實例化。 | 需由 class  implement，不可直接使用。      |
| 可以同時包含 method 與 abstract method。                    | 只能有 abstract method。                   |

## 從門來理解 abstract 與 interface 的使用時機

生活上我們會根據不同的需求使用各種類型的門，像是：為了安全而使用的防盜鎖門、為了維持和室裝潢美觀的拉門，還有專門防火的防火門等等。

這些門會因為用途的不同，而需要加裝一些額外的東西，像是：為了安全而加裝電子鎖的門。但能被叫做「門」最重要的功能不是防盜之類的功能，而是「開」和「關」。

一扇門連開跟關都做不到，那你覺得它是門嗎？當然不是！

故事來了。

PM 今天需要你寫程式來設計一道門。

而根據我們上面得出來的結論，構成門這個 class 最重要的功能是「開」和「關」，所以我們寫出了這樣的程式碼：

```php
<?php

class Door
{
    public function open() {
        echo '開門';
    }

    public function close() {
        echo '關門';
    }
}
```

然後 PM 就開始了他的「需求描述」。

PM：「诶，不是...」

PM：「我要你設計門，但你的門只有開門跟關門是怎麼回事？」

PM：「阿我鐵捲門是要往上拉，和室的拉門是要從旁邊拉，喇叭鎖門才是可以直接開門跟關門。」

PM：「你這樣寫，是要我怎麼跟客戶交代？」

經過 PM 一番精闢的解說，你發現這樣的確不行。雖然每個門都要有開和關的功能，但它們開關的方式都不一樣，所以開門與關門的操作必須等到確定是哪種門以後，才能去做實現。於是你只好加班改程式碼。

加班了一個晚上，終於改出了符合 PM 需求的程式碼😑

```php
<?php

abstract class Door
{
    // 因為不知道它怎麼開門，所以不實作。
    public abstract function open();

    // 因為不知道它怎麼關門，所以不實作。
    public abstract function close();
}

// 鐵捲門
class IronRollingDoor extends Door
{
    public function open()
    {
        echo '把鐵捲門往上拉';
    }

    public function close()
    {
        echo '把鐵捲門往下拉';
    }
}
```

想說終於開發完了的你，開開心心的拿著程式碼去給了 PM。

PM 也覺得不錯，於是你想說這下穩了，可以安心休假了。

結果剛放假，又被召回來說有緊急需求。

你：「我休個假怎麼又有問題了？」

PM：「程式碼是沒有問題辣，只是有些新的需求...」

你：「你說吧。（已經放棄溝通）」

PM：「客戶覺得你設計的門可擴充性很不錯，但他們現在想要在鐵捲門上加鎖。」

PM：「你再改一下，反正也只是加上去而已，很簡單對吧😀」

你：「😅（尷尬又不失禮貌的微笑）」

痛失美好假期的你，只好又開始為這無厘頭的需求奮鬥。

內心小劇場開始：

你：「雖然說只是加個鎖，但又不是所有的門都需要上鎖，這直接加上去鐵定出問題...」

你：「既然鎖並不是門必備的功能，那是不是應該要讓它可以隨時裝上去又拆下來？」

你：「除此之外，鎖也分很多種，各自上鎖的方式也不一樣，應該也需要一個通用的 Lock class。」

經過了一連串的內心小劇場與幾天的夜晚，終於開發出了符合新需求的程式碼。

```php
<?php

abstract class Door
{
    // 因為不知道它怎麼開門，所以不實作。
    public abstract function open();

    // 因為不知道它怎麼關門，所以不實作。
    public abstract function close();
}

// 鐵捲門
class IronRollingDoor extends Door implements Lockable
{
    public function open()
    {
        echo '把鐵捲門往上拉' . "\n";
    }

    public function close()
    {
        echo '把鐵捲門往下拉' . "\n";
    }

    public function useLock(lock $lock)
    {
        $this->close();
        $lock->locking();
    }

    public function unUseLock(Lock $lock)
    {
        $lock->unlocking();
        $this->open();
    }
}

// 這個 class 是否支援上鎖
interface Lockable
{
    public function useLock(Lock $lock);
    public function unUseLock(Lock $lock);
}

abstract class Lock
{
    // 因為不知道它怎麼上鎖，所以不實作。
    public abstract function locking();

    // 因為不知道它怎麼解鎖，所以不實作。
    public abstract function unlocking();
}

// 電子鎖
class ElectronicLock extends Lock
{
    public function locking()
    {
        echo '電子鎖上鎖' . "\n";;
    }

    public function unlocking()
    {
        echo '電子鎖解鎖' . "\n";;
    }
}

$ironRollingDoor = new IronRollingDoor();
$electronicLock  = new ElectronicLock();

$ironRollingDoor->useLock($electronicLock);
$ironRollingDoor->unUseLock($electronicLock);

/*output
 * 把鐵捲門往下拉
 * 電子鎖上鎖
 * 電子鎖解鎖
 * 把鐵捲門往上拉
 */
```

設計理念：

- 因為並不是每扇門都需要鎖，所以不能直接在 `Door` 的 class 上加鎖。
- 為了區別這扇門是否需要鎖，而採用了 `Lockable` 這樣的 interface 設計，讓使用者可以自由選擇是否要加上鎖這個功能。
- 另外，鎖又分成很多種，每種鎖的上鎖與解鎖行為也都不一樣，因此也需要一個 abstract class 的 `Lock` 來做抽象。

## 結論與看法

到這邊我們可以看到 abstract 就像是「本質上的抽象」，也就是沒有這個功能，這個 class 就沒有存在的價值。就像沒有開關功能的門，不能被叫做門一樣。

而 interface 更像是一種附加功能，可以自由添加與移除。像是哪天鐵捲門又不需要鎖的時候，只要移除掉 `Lockable` 就可以了。

`Lockable` 這樣的 interface 也可以用在 `Door` 這個 class 以外的其他 class，而不會被侷限用途。

- abstract ：abstract 就是一種「本質上的抽象」，也就是沒有這個功能，這個 class 就沒有存在的價值。
- interface：像是 class 的附加功能，可以隨時自由添加與移除。